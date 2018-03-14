<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use App\Defines\Defines;
use Cake\Utility\Hash;

class CompaniesController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('SearchSession', ['actions' => ['index']]);
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    public function index() {
        $Companies = $this->loadModel('AgcjCompanies');
        $Options = $this->loadModel('Options');

        $company_displaying = $Options->getJson(Defines::OPTION_KEY_COMPANY_DISPLAYING, []);

        $query = $Companies->find('company')
                ->contain(['AgcjOptions', 'AgcjTerms' => ['AgcjTaxonomies']])
                ->where(['ID in' => $company_displaying])
                ->order('post_title');

        $companies = $this->paginate($query);

        $this->set(compact('companies'));
    }

    public function sources() {
        $Companies = $this->loadModel('AgcjCompanies');

        $query = $Companies->find('company')
                ->contain(['AgcjOptions', 'AgcjTerms' => ['AgcjTaxonomies']])
                ->order(['post_title']);

        $companies = $this->paginate($query);

        $Options = $this->loadModel('Options');


        $company_displaying = $Options->getJson(Defines::OPTION_KEY_COMPANY_DISPLAYING, []);

        if ($this->request->is('post')) {
            $post_value = $this->request->getData('display');
            $add = array_keys(array_filter($post_value, function($var) {
                        return $var == 1;
                    }));
            $remove = array_keys(array_filter($post_value, function($var) {
                        return $var == 0;
                    }));

            $old_value = $company_displaying;

            $new_value = array_diff(array_unique(array_merge($old_value, $add)), $remove);
            sort($new_value);
            $Options->setJson(Defines::OPTION_KEY_COMPANY_DISPLAYING, $new_value);
            $company_displaying = $new_value;
        }

        $this->set(compact('companies', 'company_displaying'));
    }

}
