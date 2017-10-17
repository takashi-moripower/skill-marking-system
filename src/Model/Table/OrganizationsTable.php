<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

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
        $parent_id = Hash::get($options, 'organization_id', Hash::get($options, 'id'));
        $parent_ids = Hash::get($options, 'organization_ids', Hash::get($options, 'ids'));

        $query->join(['descendants' => [
                'table' => 'organizations',
                'type' => 'LEFT',
                'conditions' => ['descendants.lft <= ' . $this->aliasField('lft'), 'descendants.rght >= ' . $this->aliasField('rght')]
        ]]);

        if ($parent_id) {
            $query->where(['descendants.id' => $parent_id]);
        }

        if ($parent_ids) {
            $query->where(['descendants.id IN' => $parent_ids])
                    ->group($this->aliasField('id'));
        }

        return $query;
    }

    /**
     * 指定した organizationの祖先＋自身を検索
     * @param type $query
     * @param type $options
     * @return type
     */
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

}
