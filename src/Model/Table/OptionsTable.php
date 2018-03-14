<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Options Model
 *
 * @method \App\Model\Entity\Option get($primaryKey, $options = [])
 * @method \App\Model\Entity\Option newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Option[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Option|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Option patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Option[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Option findOrCreate($search, callable $callback = null, $options = [])
 */
class OptionsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('options');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
                ->scalar('option_key')
                ->requirePresence('option_key', 'create')
                ->notEmpty('option_key');

        $validator
                ->scalar('option_value')
                ->requirePresence('option_value', 'create')
                ->notEmpty('option_value');

        return $validator;
    }

    public function getByKey($key, $default = null) {
        $opt = $this->find()
                ->where(['option_key' => $key])
                ->first();

        if ($opt == null) {
            return $default;
        }

        return $opt->option_value;
    }

    public function getJson($key, $default = null) {
        $opt = $this->getByKey($key);

        if ($opt == null) {
            return $default;
        }

        return json_decode($opt);
    }

    public function setByKey($key, $value) {
        $opt = $this->find()
                ->where(['option_key'=>$key])
                ->first();
        
        if( $opt == null ){
            $opt = $this->newEntity(['option_key'=>$key]);
        }
        
        $opt->option_value = $value;
        $this->save($opt);
    }

    public function setJson($key, $value) {
        $this->setByKey($key, json_encode($value));
    }

}
