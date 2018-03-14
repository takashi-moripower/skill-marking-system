<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Session;
use App\Defines\Defines;
use Cake\Utility\Hash;


class AgcjCompaniesTable extends Table {
  
    public static function defaultConnectionName() {
        return 'agcj';
    }    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('company_posts');
        
        $this->hasMany('AgcjOptions', [
            'className'=>'AgcjOptions',
            'foreignKey' => 'post_id',
            'propertyName'=>'options'
        ]);
        
        $this->belongsToMany('AgcjTerms',[
            'joinTable' => 'company_term_relationships',
            'foreignKey' => 'object_id',
            'targetForeignKey'=>'term_taxonomy_id',
            'propertyName'=>'terms'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {

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


        return $rules;
    }
    
    public function findCompany( $query ){
        return $query->where([$this->aliasField('post_type') => 'company']);
    }

}
