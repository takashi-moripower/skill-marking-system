<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

/**
 * Users Model
 *
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\EngineersTable|\Cake\ORM\Association\HasMany $Engineers
 * @property \App\Model\Table\MarkersTable|\Cake\ORM\Association\HasMany $Markers
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->addBehavior('Acl.Acl', ['type' => 'requester']);
        $this->addBehavior('Search.Search');

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id'
        ]);
        $this->hasMany('Works', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Engineers');

        $this->belongsToMany('Organizations');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create')
                ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
                ->scalar('name')
                ->allowEmpty('name');

        $validator
                ->email('email')
                ->notEmpty('email')
                ;

        $validator
                ->scalar('password')
                ->allowEmpty('password');

        $validator
                ->scalar('google')
                ->allowEmpty('google');

        $validator
                ->scalar('facebook')
                ->allowEmpty('facebook');

        $validator
                ->scalar('twitter')
                ->allowEmpty('twitter');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['id']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['group_id'], 'Groups'));

        return $rules;
    }

    /**
     * ログイン時、セッションに保存するデータ
     * @param type $user_id
     */
    public function getSessionData($user_id) {
        $user = $this->find()
                ->where(['Users.id' => $user_id])
                ->contain(['groups'])
                ->select(['Users.id', 'Users.name', 'Users.group_id', 'Groups.name'])
                ->first()
        ;

        return $user;
    }

    /**
     * 該当組織のメンバーを取得
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findMembers($query, $options) {
        $tableOU = TableRegistry::get('organizations_users');
        $orgs = Hash::get($options, 'organization_id');

        $members = $tableOU->find()
                ->where(['organization_id IN' => $orgs])
                ->select('organizations_users.user_id');

        $query->where(['Users.id IN' => $members]);

        return $query;
    }

    /**
     * 特定ユーザーの所属組織を取得
     * @param type $user_id
     * @return type
     */
    public function getOrganizations($user_id) {
        $tableOU = TableRegistry::get('organizations_users');

        $orgs = $tableOU->find()
                ->where(['user_id' => $user_id])
                ->select(['organization_id'])
                ->toArray();

        return Hash::extract($orgs, '{n}.organization_id');
    }

    /**
     * @return \Search\Manager
     */
    public function searchManager() {
        /** @var \Search\Manager $searchManager */
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
                ->finder('sex')
                ->finder('age')
                ->finder('skill', ['finder' => 'Skills'])
                ->finder('organization_id', ['finder' => 'RootOrganization'])
                ->finder('condition_id',['finder'=>'Condition'])
                ->value('group_id')
                ->like('name',['field'=>'name','before'=>true,'after'=>true])
        ;


        return $searchManager;
    }
    
    public function findAge($query , $options){
        $age_max = Hash::get($options,$age_max);
        $age_min = Hash::get($options,$age_min);
    }

    public function findSex($query, $options) {
        if (empty($options['sex'])) {
            return $query;
        }
        
        $query->where(['Engineers.sex' => $options['sex']]);
        return $query;
    }

    public function findSkills($query, $options) {

        foreach ($options['skill'] as $skill) {
            if ($skill['id']) {
                $query->find('skill', ['skill' => $skill]);
            }
        }

        return $query;
    }
    /**
     * 特定スキルの所持者を取得
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findSkill($query, $options) {

        $skill_id = Hash::get($options, 'skill.id');
        $skill_level = Hash::get($options, 'skill.level');

        if (empty($skill_id) || empty($skill_level)) {
            return $query;
        }

        $tableSW = TableRegistry::get('skills_works');
        $tableW = TableRegistry::get('works');

        $user_ids = $tableW->find()
                ->LeftJoin('skills_works', ['skills_works.work_id = works.id'])
                ->where([
                    'skills_works.skill_id' => $skill_id,
                    'skills_works.level IN' => $skill_level,
                    'skills_works.user_id != works.user_id'
                ])
                ->select('user_id');

        $query->where([$this->aliasField('id') . ' IN' => $user_ids]);

        return $query;
    }

    /**
     * 特定組織及びその下位組織に所属するユーザーを取得
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findRootOrganization($query, $options) {
        $root_id = Hash::get($options, 'organization_id');

        $EndOrgs = TableRegistry::get('EndOrgs', ['table' => 'organizations'])
                ->find()
                ->leftJoin(['RootOrgs' => 'organizations'], ['RootOrgs.lft <= EndOrgs.lft', 'RootOrgs.rght >= EndOrgs.rght'])
                ->leftJoin('organizations_users', 'organizations_users.organization_id = EndOrgs.id')
                ->where(['RootOrgs.id' => $root_id])
                ->select('organizations_users.user_id')
                ->group('organizations_users.user_id');

        $query->where([$this->aliasField('id') . ' IN' => $EndOrgs]);

        return $query;
    }

    /**
     * （組織管理者が）編集可能なユーザーを取得
     * @param type $query
     * @param type $options
     */
    public function findEditable($query, $options) {
        $user_id = Hash::get($options, 'user_id');

        $orgs = TableRegistry::get('Organizations')
                ->find('user', ['user_id' => $user_id, 'relation' => 'children'])
                ->select('id');


        $members = TableRegistry::get('organizations_users')
                ->find()
                ->where(['organization_id IN' => $orgs])
                ->group('user_id')
                ->select('user_id');

        $query->where([$this->aliasField('id') . ' IN' => $members]);
        return $query;
    }
    
    /**
     * findEditableに丸投げ
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findStudents($query,$options){
        $user_id = Hash::get($options,'teacher_id' , Hash::get($options,'user_id'));
        
        return $query->find('Editable',compact('user_id'));
    }

    public function beforeDelete($id) {


        /*
          そのユーザーが採点したスキル情報を削除する処理　現在停止中
          $tableSW = TableRegistry::get('skills_works');
          $tableSW->query()
          ->delete()
          ->where(['user_id'=>$id])
          ->execute();
         */
        return true;
    }
    
    public function findCondition( $query , $options ){
        $condition = TableRegistry::get('Conditions')->get($options['condition_id'],['contain'=>['Skills','ConditionOptions']]);
              
        foreach( $condition->skills as $skill ){
            $query->find('skill',['skill'=>['id'=>$skill->id , 'level'=> SkillsTable::flags2Array( $skill->_joinData->levels )]]);
        }
        
        return $query ;
    }

}
