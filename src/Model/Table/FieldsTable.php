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
        $this->addBehavior('Depth');
        $this->addBehavior('Path');

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
                ->allowEmpty('id', 'parent_id');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentFields', ['allowNullableNulls' => true]));

        $rules->add([$this, 'isValidOrg'], 'validOrg', [
            'errorField' => 'organization_id',
            'message' => '親と異なる組織に属することはできません'
        ]);
        return $rules;
    }

    public function isValidOrg($value, $context) {

        if ($value->parent_id === null) {
            return true;
        }

        $tableF = TableRegistry::get('fields');
        $parent = $tableF->get($value->parent_id);

        return ( $parent->organization_id == $value->organization_id );
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
        $user_id = Hash::get($options, 'user_id');
        $user_ids = Hash::get($options, 'user_ids');


        if ($user_id) {
            $user_id = $options['user_id'];
            $group_id = TableRegistry::get('users')->get($user_id)->group_id;

            switch ($group_id) {
                case Defines::GROUP_ADMIN:
                    return $query;

                default:
                    $parents = $this->Organizations->find('user', ['user_id' => $user_id, 'relation' => 'parents']);
                    $parents->select('id');

                    $children = $this->Organizations->find('user', ['user_id' => $user_id, 'relation' => 'children']);
                    $children->select('id');


                    $query->where(['or' => [
                            $this->aliasField('organization_id') . ' in' => $parents,
                            $this->aliasField('organization_id') . ' IN' => $children,
                            $this->aliasField('organization_id') . ' IS' => null
                    ]]);
                    return $query;
            }

            return $query;
        }

        if ($user_ids) {
            foreach ($user_ids as $user_id) {
                $query->find('usable', ['user_id' => $user_id]);
            }
            return $query;
        }
    }

    public function findEditable($query, $options) {
        $user_id = $options['user_id'];
        $group_id = TableRegistry::get('users')->get($user_id)->group_id;


        switch ($group_id) {
            case Defines::GROUP_ADMIN:
                return $query;


            case Defines::GROUP_MARKER:
            case Defines::GROUP_ENGINEER:
                return $query->where('FALSE');


            case Defines::GROUP_ORGANIZATION_ADMIN:
                $children = $this->Organizations->find('user', ['user_id' => $user_id, 'relation' => 'children'])
                        ->select($this->Organizations->aliasField('id'));


                $query->where(['or' => [
                        $this->aliasField('organization_id') . ' IN' => $children,
                ]]);
                return $query;
        }

        return $query;
    }

    public function findCountSkills($query, $options) {

        $subquery = TableRegistry::get('skills')
                ->find()
                ->where(['field_id =' . $this->aliasField('id')])
                ->select(['count' => 'count(skills.id)']);

        $query
                ->select(['skill_count' => $subquery]);
        return $query;
    }

    public function findCountSkillsChildren($query, $options) {
        $children = TableRegistry::get('Descendants', ['table' => 'fields'])
                ->find()
                ->where(['Descendants.lft >=' . $this->aliasField('lft'), 'Descendants.rght <=' . $this->aliasField('rght')])
                ->select('Descendants.id');



        $skills = TableRegistry::get('Skills')->find()
                ->where(['Skills.field_id IN' => $children])
                ->select(['count' => 'count(Skills.id)']);

        $query->select(['skill_count_children' => $skills]);

        return $query;
    }

    public function findSetEditable($query, $options) {
        $user_id = Hash::get($options, 'user_id');

        $fieldsEditable = TableRegistry::get('FieldsEditable', ['table' => 'fields', 'className' => '\App\Model\Table\FieldsTable'])
                ->find('editable', ['user_id' => $user_id])
                ->select('id');

        $query
                ->select(['editable' => $query->newExpr()->addCase([
                        $query->newExpr()->add([$this->aliasField('id') . ' IN' => $fieldsEditable]),
                        1,
                        'boolean'
                    ])
        ]);

        return $query;
    }

}
