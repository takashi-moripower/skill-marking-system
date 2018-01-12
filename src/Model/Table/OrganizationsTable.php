<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Defines\Defines;

/**
 * Organizations Model
 *
 * @property \App\Model\Table\OrganizationsTable|\Cake\ORM\Association\BelongsTo $ParentOrganizations
 * @property \App\Model\Table\FieldsTable|\Cake\ORM\Association\HasMany $Fields
 * @property \App\Model\Table\OrganizationsTable|\Cake\ORM\Association\HasMany $ChildOrganizations
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\Organization get($primaryKey, $options = [])
 * @method \App\Model\Entity\Organization newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Organization[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Organization|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Organization patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Organization[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Organization findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class OrganizationsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('organizations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');
        $this->addBehavior('Depth');
        $this->addBehavior('Path');

        $this->belongsTo('ParentOrganizations', [
            'className' => 'Organizations',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Fields', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('ChildOrganizations', [
            'className' => 'Organizations',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'organization_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'organizations_users'
        ]);
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
                ->allowEmpty('id', 'create');

        $validator
                ->scalar('name')
                ->allowEmpty('name');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentOrganizations'));

        return $rules;
    }

    /**
     * そのユーザーが所属する組織　及びその親/子組織を返す
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findUser($query, $options) {
        $user_id = Hash::get($options, 'user_id', 0);
        $relation = Hash::get($options, 'relation', 'parents');

        $orgs = TableRegistry::get('organizations_users')
                ->find()
                ->where(['user_id' => $user_id])
                ->select('organization_id');

        if ($relation == 'parents') {
            $query->find('ancestors', ['ids' => $orgs]);
        } elseif ($relation == 'children') {
            $query->find('descendants', ['ids' => $orgs]);
        } else {
            $query->where([$this->AliasField('id') . ' IN' => $orgs]);
        }


        return $query;
    }

    /**
     */
    public function findCountUsers($query, $options) {

        $tableOA = TableRegistry::get('OA', ['table' => 'users']);

        $OA = $tableOA->find()
                ->leftJoin('organizations_users', [$tableOA->aliasField('id') . '= organizations_users.user_id'])
                ->where([$tableOA->aliasField('group_id') => Defines::GROUP_ORGANIZATION_ADMIN])
                ->where([$this->aliasField('id') . ' = organizations_users.organization_id'])
                ->select(['count' => 'count(OA.id)']);

        $tableMK = TableRegistry::get('MK', ['table' => 'users']);
        $MK = $tableMK->find()
                ->leftJoin('organizations_users', [$tableMK->aliasField('id') . '= organizations_users.user_id'])
                ->where([$tableMK->aliasField('group_id') => Defines::GROUP_MARKER])
                ->where([$this->aliasField('id') . ' = organizations_users.organization_id'])
                ->select(['count' => 'count(MK.id)']);

        $tableEG = TableRegistry::get('EG', ['table' => 'users']);
        $EG = $tableEG->find()
                ->leftJoin('organizations_users', [$tableEG->aliasField('id') . '= organizations_users.user_id'])
                ->where([$tableEG->aliasField('group_id') => Defines::GROUP_ENGINEER])
                ->where([$this->aliasField('id') . ' = organizations_users.organization_id'])
                ->select(['count' => 'count(EG.id)']);


        $query
                ->select(['count_org_admin' => $OA])
                ->select(['count_marker' => $MK])
                ->select(['count_engineer' => $EG])
        ;


        return $query;
    }

    public function transferMember($from, $to, $remove = true) {

        $tableOU = TableRegistry::get('organizations_users');

        $fromMembers = $tableOU
                ->find('list', ['valueField' => 'user_id'])
                ->where(['organization_id' => $from])
                ->select('user_id')
                ->toArray();

        $toMembers = $tableOU
                ->find('list', ['valueField' => 'user_id'])
                ->where(['organization_id' => $to])
                ->select('user_id')
                ->toArray();

        $transformMembers = array_diff($fromMembers, $toMembers);

        $tableOU->query()
                ->update()
                ->where(['user_id IN' => $transformMembers, 'organization_id' => $from])
                ->set(['organization_id' => $to])
                ->execute();

        if ($remove) {
            $tableOU->query()
                    ->delete()
                    ->where(['organization_id' => $from])
                    ->execute();
        }
    }

    /**
     * Home画面統計表示用
     * @param type $query
     * @param type $options
     */
    public function findHome($query, $options) {
        $user_id = Hash::get($options, 'user_id');

        $query->find('pathName')
                ->select($this)
                ->find('user', ['user_id' => $user_id,'relation'=>'children']);

        return $query;
    }
}
