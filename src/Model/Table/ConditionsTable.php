<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Conditions Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ConditionOptionsTable|\Cake\ORM\Association\HasMany $ConditionOptions
 * @property \App\Model\Table\SkillsTable|\Cake\ORM\Association\BelongsToMany $Skills
 *
 * @method \App\Model\Entity\Condition get($primaryKey, $options = [])
 * @method \App\Model\Entity\Condition newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Condition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Condition|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Condition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Condition[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Condition findOrCreate($search, callable $callback = null, $options = [])
 */
class ConditionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('conditions');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ConditionOptions', [
            'foreignKey' => 'condition_id'
        ]);
        $this->belongsToMany('Skills', [
            'foreignKey' => 'condition_id',
            'targetForeignKey' => 'skill_id',
            'joinTable' => 'conditions_skills'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('title')
            ->allowEmpty('title');

        $validator
            ->scalar('note')
            ->allowEmpty('note');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
