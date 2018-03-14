<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AgcjTermsTable extends Table {

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
        $this->setTable('company_terms');

        $this->hasOne('AgcjTaxonomies', [
            'foreignKey' => 'term_id',
            'bindingKey' => 'term_id',
            'propertyName' => 'taxonomy'
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

    public function findArea($query) {
        $query
                ->contain('AgcjTaxonomies')
                ->where(['AgcjTaxonomies.taxonomy' => 'area'])
                ->order(['AgcjTaxonomies.parent', 'AgcjTerms.term_id']);
        return $query;
    }

}
