<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;
use Cake\Network\Session;

/**
 * Conditions Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ConditionOptionsTable|\Cake\ORM\Association\HasMany $ConditionOptions
 * @property \App\Model\Table\ConditionsSkillsTable|\Cake\ORM\Association\HasMany $ConditionsSkills
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
class ConditionsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->addBehavior('Search.Search');

        $this->setTable('conditions');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ConditionOptions', [
            'dependent' => true,
            'foreignKey' => 'condition_id'
        ]);
        $this->belongsToMany('Skills', [
            'foreignKey' => 'condition_id',
            'targetForeignKey' => 'skill_id',
            'joinTable' => 'conditions_skills'
        ]);

        $this->hasMany('ConditionsSkills');
        $this->hasMany('Contacts');
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
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function afterSave($event, $entity, $options) {
        if (isset($entity->skill_and_levels)) {
            $this->saveSkillAndLevels($entity);
        }

        return true;
    }

    public function saveSkillAndLevels($entity) {
        $skillIds = array_keys($entity->skill_and_levels);

        $this->ConditionsSkills->query()
                ->delete()
                ->where(['skill_id NOT IN' => $skillIds, 'condition_id' => $entity->id])
                ->execute();


        foreach ($entity->skill_and_levels as $skillId => $levels) {
            $this->saveSkill($entity->id, $skillId, $levels);
        }
    }

    public function saveSkill($conditionId, $skillId, $levels) {
        if (empty($levels)) {
            $this->unlinkSkill($conditionId, $skillId);
            return;
        }

        if (is_array($levels)) {
            $levels = SkillsTable::array2flags($levels);
        }

        $CS = $this->ConditionsSkills->find()
                ->where(['condition_id' => $conditionId, 'skill_id' => $skillId])
                ->first();

        if (empty($CS)) {
            $CS = $this->ConditionsSkills->newEntity(['condition_id' => $conditionId, 'skill_id' => $skillId]);
        }

        $CS->levels = $levels;

        $this->ConditionsSkills->save($CS);
    }

    public function unlinkSkill($conditionId, $skillId) {
        if ($skillId == 0) {
            return;
        }

        $this->ConditionsSkills->query()
                ->delete()
                ->where(['condition_id' => $conditionId, 'skill_id' => $skillId])
                ->execute();
    }

    /**
     * 募集条件にマッチするユーザーを取得
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findUser($query, $options) {
        $query->find('UserSkill', $options);
        return $query;
    }

    public function findUserSkill($query, $options) {

        $user_id = Hash::get($options, 'user_id');

        $tableCS = TableRegistry::get('ConditionsSkills');

        $skillsMatches = $tableCS->find()
                ->select([
                    'condition_id' => 'ConditionsSkills.condition_id',
                    'count' => 'count(DISTINCT ConditionsSkills.id)'
                ])
                ->join([
                    'SkillsWorks' => [
                        'table' => 'skills_works',
                        'type' => 'inner',
                        'conditions' => [
                            'SkillsWorks.skill_id =' . $tableCS->aliasField('skill_id'),
                            '( POWER( 2 , SkillsWorks.level-1) & ConditionsSkills.levels ) <> 0'
                        ]
                    ],
                    'Works' => [
                        'table' => 'works',
                        'type' => 'inner',
                        'conditions' => [
                            'SkillsWorks.work_id = Works.id',
                            'Works.user_id' => $user_id,
                            'Works.user_id <> SkillsWorks.user_id'
                        ]
                    ]
                ])
                ->group('ConditionsSkills.condition_id');

        $skillsAll = $tableCS->find()
                ->select([
                    'condition_id' => 'ConditionsSkills.condition_id',
                    'count' => 'count(DISTINCT ConditionsSkills.id)'
                ])
                ->group('ConditionsSkills.condition_id');

        $query->join([
                    'skills_matches' => [
                        'table' => $skillsMatches,
                        'type' => 'inner',
                        'conditions' => [
                            'skills_matches.condition_id = Conditions.id'
                        ]
                    ],
                    'skills_all' => [
                        'table' => $skillsAll,
                        'type' => 'inner',
                        'conditions' => [
                            'skills_all.condition_id = Conditions.id'
                        ]
                    ]
                        ]
                )
                ->where(['skills_matches.count = skills_all.count']);

        return $query;
    }

    /**
     * match というパラメータを各Entityにセット
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findSetMatch($query, $options) {
        $user_id = Hash::get($options, 'user_id');

        $matchingConditions = $this->find('user', ['user_id' => $user_id])
                ->select('id');

        $query
                ->select(['match' => $query->newExpr()->addCase([
                        $query->newExpr()->add([$this->aliasField('id') . ' IN' => $matchingConditions]),
                        1,
                        'boolean'
                    ])
        ]);
        return $query;
    }

    /**
     * @return \Search\Manager
     */
    public function searchManager() {

        /** @var \Search\Manager $searchManager */
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager
                ->value('user_id')
                ->like('title', ['field' => 'title', 'before' => true, 'after' => true])
                ->finder('match')
        ;


        return $searchManager;
    }

    /**
     * 検索時、ログインユーザーに条件適合するもののみ表示
     * 実装はfindUserに丸投げ
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findMatch($query, $options) {
        $session = new Session();
        $loginUserId = $session->read('Auth.User.id');
        $userId = Hash::get($options, 'user_id', $loginUserId);
        $query->find('user', ['user_id' => $userId]);
        return $query;
    }

    public function beforeSave($event, $entity, $options) {
        $ContitionOptions = TableRegistry::get('ConditionOptions');

        foreach ($entity->condition_options as $option_id => $option) {
            if( $option->value == null || $option->value == '' ){
                $ContitionOptions->query()
                        ->delete()
                        ->where(['condition_id'=>$entity->id,'type'=>$option->type])
                        ->execute();
                
                unset( $entity->condition_options[$option_id] );
            }
        }
        
        return true;
    }

}
