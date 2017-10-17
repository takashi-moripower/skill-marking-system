<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Model\Entity\Skill;
use App\Defines\Defines;

/**
 * Skills Model
 *
 * @property \App\Model\Table\FieldsTable|\Cake\ORM\Association\BelongsTo $Fields
 * @property \App\Model\Table\WorksTable|\Cake\ORM\Association\BelongsToMany $Works
 *
 * @method \App\Model\Entity\Skill get($primaryKey, $options = [])
 * @method \App\Model\Entity\Skill newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Skill[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Skill|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Skill patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Skill[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Skill findOrCreate($search, callable $callback = null, $options = [])
 */
class SkillsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('skills');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Fields', [
            'foreignKey' => 'field_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsToMany('Works', [
            'foreignKey' => 'skill_id',
            'targetForeignKey' => 'work_id',
            'joinTable' => 'skills_works'
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
                ->requirePresence('name', 'create')
                ->notEmpty('name');

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
        $rules->add($rules->existsIn(['field_id'], 'Fields'));

        return $rules;
    }

    public function createFromArray($src) {
        foreach ($src as $fieldName => $skills) {
            $field = $this->Fields->find()
                    ->where(['name' => $fieldName])
                    ->first();

            $fieldId = Hash::get($field, 'id', null);
            foreach ($skills as $skillName) {
                $newEntity = new Skill;
                $newEntity->name = $skillName;
                $newEntity->field_id = $fieldId;

                $this->save($newEntity);
            }
        }
    }

    /**
     * あるユーザーが利用可能なスキルを取得　配列を渡された場合はAND
     * @param type $query
     * @param type $options
     * @return type
     */
    public function findUsable($query, $options) {
        if (isset($options['group_id']) && $options['group_id'] == Defines::GROUP_ADMIN) {
            return $query;
        }

        if (isset($options['user_id'])) {
            $user_id = $options['user_id'];

            $fields = $this->Fields->find('usable', ['user_id' => $user_id])
                    ->select('id');

            $query->where([$this->aliasField('field_id') . ' IN' => $fields]);
            return $query;
        }

        if (isset($options['user_ids'])) {
            $user_ids = $options['user_ids'];
            
            $cases = [];
            foreach( $user_ids as $user_id){
                $fields = $this->Fields->find('usable', ['user_id' => $user_id])
                        ->select('id');
                
                $cases[] = [$this->aliasField('field_id') . ' IN' => $fields];
            }


            $query->where(['and'=>$cases]);

            return $query;
        }
    }

    public function findMaxLevel($query, $options) {

        $user_id = $options['user_id'];

        $tableW = TableRegistry::get('works');
        $tableSW = TableRegistry::get('skills_works');
        
        

        $SW1 = $tableSW->find()
                ->select(['skill_id'=>'skill_id','max_level' => 'max(level)'])
                ->leftJoin('works','works.id = skills_works.work_id')
                ->where(['works.user_id' => $user_id])
                ->group(['skills_works.skill_id'])
                ;
                
        $SW2 = $tableSW->find()
                ->leftJoin('works' , 'works.id = '.$tableSW->aliasField('work_id'))
                ->innerJoin([ 'SW1' => $SW1 ],[ 'SW1.max_level = '.$tableSW->aliasField('level') , 'SW1.skill_id ='.$tableSW->aliasField('skill_id')])
                ->where([$tableW->aliasField('user_id') => $user_id ])
                ->select(['skill_id'=>'skills_works.skill_id' , 'level'=>'skills_works.level']);
        
        $query
                ->innerJoin(['SW2' => $SW2 ],['SW2.skill_id = skills.id'])
                ->group('SW2.skill_id')
                ->select($this)
                ->select(['level'=>'SW2.level']);


        return $query;
    }

}
