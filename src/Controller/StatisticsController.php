<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Defines\Defines;
use App\Utility\MyUtil;
use App\Utility\Statistics;

/**
 * Works Controller
 *
 * @property \App\Model\Table\WorksTable $Works
 *
 * @method \App\Model\Entity\Work[] paginate($object = null, array $settings = [])
 */
class StatisticsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('SearchSession', ['actions' => ['skills']]);
    }

    public function index() {
        
    }

    public function skills() {
        $statistics = new Statistics($this->_getQuery());
        $organizations = $this->_getOrganizations();
        $junles = $this->_getJunles();
        $this->set(compact('statistics', 'organizations', 'junles'));
    }

    protected function _getQuery() {
        $query = TableRegistry::get('SkillsWorks')->find();
        $data = $this->request->data();

        $organization_id = Hash::get($data, 'organization_id', null);
        $min_age = Hash::get($data, 'min_age', null);
        $max_age = Hash::get($data, 'max_age', null);
        $sex = Hash::get($data, 'sex', null);
        $junle_id = Hash::get($data, 'junle_id', null);

        $users = TableRegistry::get('Users')->find()
                ->select('Users.id');

        $works = TableRegistry::get('Works')->find()
                ->select('Works.id');

        $userSelected = false;
        $workSelected = false;

        if (!empty($organization_id)) {
            $users->find('RootOrganization', ['organization_id' => $organization_id]);
            $userSelected = true;
        }
        if (!empty($max_age)) {
            $users
                    ->contain('Engineers')
                    ->find('maxAge', ['max_age' => $max_age]);
            $userSelected = true;
        }

        if (!empty($min_age)) {
            $users
                    ->contain('Engineers')
                    ->find('minAge', ['min_age' => $min_age]);
            $userSelected = true;
        }

        if (!empty($sex) && $sex != Defines::SEX_INDIFFARENCE) {
            $users
                    ->contain('Engineers')
                    ->find('sex', ['sex' => $sex]);
            $userSelected = true;
        }

        if ($userSelected) {
            $works
                    ->where(['Works.user_id IN' => $users]);
            $workSelected = true;
        }

        if ($junle_id) {
            $worksByJunle = TableRegistry::get('JunlesWorks')
                    ->find()
                    ->where(['junle_id'=>$junle_id])
                    ->select('work_id');
            
            $works
                    ->where(['Works.id IN' => $worksByJunle ]);
            
        }
        
        if( $workSelected ){
            $query->where(['SkillsWorks.work_id IN' => $works]);
        }

        return $query;
    }

    protected function _getOrganizations() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');
        $tableO = TableRegistry::get('Organizations');

        //検索フォーム用データ
        $organizations = $tableO->find()
                ->find('pathName')
                ->select($tableO->aliasField('id'))
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);

        if ($loginUserGroup == Defines::GROUP_MARKER || $loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN) {
            $organizations->find('user', ['user_id' => $loginUserId, 'relation' => 'children']);
        }

        return $organizations;
    }

    protected function _getJunles() {
        $tableJ = TableRegistry::get('Junles');
        $junles = $tableJ->find('list');

        return $junles;
    }

}
