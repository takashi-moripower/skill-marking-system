<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

/**
 * Fields Model
 *
 * @property \App\Model\Table\OrganizationsTable|\Cake\ORM\Association\BelongsTo $Organizations
 * @property \App\Model\Table\FieldsTable|\Cake\ORM\Association\BelongsTo $ParentFields
 * @property \App\Model\Table\FieldsTable|\Cake\ORM\Association\HasMany $ChildFields
 * @property \App\Model\Table\SkillsTable|\Cake\ORM\Association\HasMany $Skills
 *
 * @method \App\Model\Entity\Field get($primaryKey, $options = [])
 * @method \App\Model\Entity\Field newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Field[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Field|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Field patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Field[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Field findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class FieldsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('fields');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('Organizations', [
            'foreignKey' => 'organization_id'
        ]);
        $this->belongsTo('ParentFields', [
            'className' => 'Fields',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildFields', [
            'className' => 'Fields',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Skills', [
            'foreignKey' => 'field_id'
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
        $rules->add($rules->existsIn(['organization_id'], 'Organizations'));
        $rules->add($rules->existsIn(['parent_id'], 'ParentFields'));

        return $rules;
    }

    public function createFromArray($src, $parent_id = null) {
        foreach ($src as $name => $children) {
            if (!is_array($children)) {
                $name = $children;
                $children = null;
            }

            $newEntity = new \App\Model\Entity\Field([
                'name' => $name,
                'parent_id' => $parent_id,
            ]);
            $this->save($newEntity);
            if (is_array($children)) {
                $this->createFromArray($children, $newEntity->id);
            }
        }
    }

    /*
     * user_idから利用可能なfieldを取得
     * 配列渡されたときはAND
     */

    public function findUsable($query, $options) {
        $tableOU = TableRegistry::get('organizations_users');

        $user_id = Hash::get($options, 'user_id');

        if (is_array($user_id)) {
            foreach ($user_id as $uid) {
                $query->find('usable', ['user_id' => $uid]);
            }

            return $query;
        }

        if (is_numeric($user_id)) {
            $orgs = $tableOU->find()
                    ->where([$tableOU->aliasField('user_id') => $user_id])
                    ->select($tableOU->aliasField('organization_id'));

            $query->where(['or' => [$this->aliasField('organization_id') . ' IS' => NULL, $this->aliasField('organization_id') . ' IN' => $orgs]]);
            return $query;
        }

        return $query;
    }

}
