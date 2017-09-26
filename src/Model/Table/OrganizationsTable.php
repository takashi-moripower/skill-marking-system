<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

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
     * そのユーザーが所属する組織を返す
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findUser($query, $options) {
        $tableOU = TableRegistry::get('organizations_users');

        $user_id = $options['user_id'];

        $endOrgs = $this->find()
                ->leftJoin('organizations_users', 'organizations_users.organization_id = ' . $this->aliasField('id'))
                ->where(['organizations_users.user_id' => $user_id]);

        foreach ($endOrgs as $endOrg) {
            $query
                    ->orWhere(['and' => [$this->aliasField('lft') . ' >=' => $endOrg->lft, $this->aliasField('rght') . ' <=' => $endOrg->rght]]);
        }

        return $query;
    }

}
