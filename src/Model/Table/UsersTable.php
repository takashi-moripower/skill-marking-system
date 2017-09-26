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
        $this->hasMany('SkillsWorks', [
            'foreignKey' => 'marker_id'
        ]);

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

    public function findMembers($query, $options) {
        $tableOU = TableRegistry::get('organizations_users');
        $orgs = Hash::get($options, 'organization_id');

        $members = $tableOU->find()
                ->where(['organization_id IN' => $orgs])
                ->select('organizations_users.user_id');

        $query->where(['Users.id IN' => $members]);

        return $query;
    }

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
                ->finder('skill_id', ['finder' => 'Skills'])
        ;


        return $searchManager;
    }

    public function findSkills($query, $options) {
        foreach ($options['skill_id'] as $index => $skill_id) {

            $query->find('skill', [
                'skill_id' => $skill_id,
                'skill_level' => Hash::get($options, 'search.skill_level.' . $index)
            ]);
        }

        return $query;
    }

    public function findSkill($query, $options) {

        $skill_id = Hash::get($options, 'skill_id', Hash::get($options, 'search.skill_id'));
        $skill_level = Hash::get($options, 'skill_level', Hash::get($options, 'search.skill_level'));

        if (empty($skill_id) || empty($skill_level)) {
            return $query;
        }

        $tableSW = TableRegistry::get('skills_works');
        $tableW = TableRegistry::get('works');

        $work_ids = $tableSW->find()
                ->select($tableSW->aliasField('work_id'))
                ->where([
            $tableSW->aliasField('skill_id') => $skill_id,
            $tableSW->aliasField('level') . ' IN' => $skill_level
        ]);


        $user_ids = $tableW->find()
                ->where([$tableW->aliasField('id') . ' IN' => $work_ids])
                ->select($tableW->aliasField('user_id'));

        $query->where([$this->aliasField('id') . ' IN' => $user_ids]);


        return $query;
    }

}
