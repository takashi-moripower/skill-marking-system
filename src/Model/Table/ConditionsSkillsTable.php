<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConditionsSkills Model
 *
 * @property \App\Model\Table\ConditionsTable|\Cake\ORM\Association\BelongsTo $Conditions
 * @property \App\Model\Table\SkillsTable|\Cake\ORM\Association\BelongsTo $Skills
 *
 * @method \App\Model\Entity\ConditionsSkill get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConditionsSkill newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConditionsSkill[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConditionsSkill|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConditionsSkill patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConditionsSkill[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConditionsSkill findOrCreate($search, callable $callback = null, $options = [])
 */
class ConditionsSkillsTable extends Table
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

        $this->setTable('conditions_skills');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Conditions', [
            'foreignKey' => 'condition_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Skills', [
            'foreignKey' => 'skill_id',
            'joinType' => 'INNER'
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
            ->integer('levels')
            ->requirePresence('levels', 'create')
            ->notEmpty('levels');

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
        $rules->add($rules->existsIn(['condition_id'], 'Conditions'));
        $rules->add($rules->existsIn(['skill_id'], 'Skills'));

        return $rules;
    }
}
