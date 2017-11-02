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
            $query->find('parents', ['ids' => $orgs]);
        } elseif ($relation == 'children') {
            $query->find('children', ['ids' => $orgs]);
        } else {
            $query->where([$this->aliasField('id') . ' IN' => $orgs]);
        }

        return $query;
    }

    /**
     * 指定した organizationの子孫＋自身を検索
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findChildren($query, $options) {
        $parent_id = Hash::get($options, 'organization_id', Hash::get($options, 'id', null));
        $parent_ids = Hash::get($options, 'organization_ids', Hash::get($options, 'ids', []));

        $tableParents = TableRegistry::get('Parents', ['table' => 'organizations']);

        $parents = $tableParents->find();

        if (!empty($parent_id)) {
            $parents->where([$tableParents->aliasField('id') => $parent_id]);
        }

        if (!empty($parent_ids)) {
            $parents->where([$tableParents->aliasField('id') . ' IN' => $parent_ids]);
        }

        $parents->join(['Descendants' => [
                'table' => 'organizations',
                'type' => 'inner',
                'conditions' => [
                    'Descendants.lft >= Parents.lft',
                    'Descendants.rght <= Parents.rght',
                ]
        ]]);


        $descendants = $parents
                ->select(['Descendants_id' => 'Descendants.id'])
                ->group('Descendants_id');

        $query->where([$this->aliasField('id') . ' IN' => $descendants]);


        return $query;
    }

    /**
     * 指定した organizationの祖先＋自身を検索
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findParents($query, $options) {
        $child_id = Hash::get($options, 'organization_id', Hash::get($options, 'id', null));
        $child_ids = Hash::get($options, 'organization_ids', Hash::get($options, 'ids', []));

        $tableChildren = TableRegistry::get('Children', ['table' => 'organizations']);

        $children = $tableChildren->find();

        if (!empty($child_id)) {
            $children->where([$tableChildren->aliasField('id') => $child_id]);
        }

        if (!empty($child_ids)) {
            $children->where([$tableChildren->aliasField('id') . ' IN' => $child_ids]);
        }

        $children->join(['Ancestors' => [
                'table' => 'organizations',
                'type' => 'inner',
                'conditions' => [
                    'Ancestors.lft <= Children.lft',
                    'Ancestors.rght >= Children.rght',
                ]
        ]]);

        $ancestors = $children
                ->select(['Ancestror_id' => 'Ancestors.id'])
                ->group('Ancestror_id');

        $query->where([$this->aliasField('id') . ' IN' => $ancestors]);


        return $query;
    }

    /*
      public function findParents($query, $options) {
      $child_id = Hash::get($options, 'organization_id', Hash::get($options, 'id'));
      $child_ids = Hash::get($options, 'organization_ids', Hash::get($options, 'ids'));

      $query->join(['ancestors' => [
      'table' => 'organizations',
      'type' => 'LEFT',
      'conditions' => ['ancestors.lft >= ' . $this->aliasField('lft'), 'ancestors.rght <= ' . $this->aliasField('rght')]
      ]]);

      if ($child_id) {
      $query->where(['ancestors.id' => $child_id]);
      }

      if ($child_ids) {
      $query->where(['ancestors.id IN' => $child_ids])
      ->group($this->aliasField('id'));
      }

      return $query;
      }
     */

    /**
     * フォーム用選択肢を返す
     * @param type $query
     * @return string
     */
    public function getSelectorFromQuery($query) {
        $list = $query->find('depth')
                ->select($this)
                ->order($this->aliasField('lft'));

        $result = [];

        foreach ($list as $org) {
            $prefix = "";
            for ($i = 0; $i < $org->depth; $i++) {
                $prefix .= '　';
                if ($i == $org->depth - 1) {
                    $prefix .= '↳';
                }
            }
            $result[$org->id] = $prefix . $org->name;
        }

        return $result;
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

}
