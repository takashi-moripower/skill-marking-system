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
        $this->set(compact('statistics', 'organizations'));
    }

    protected function _getQuery() {
        $query = TableRegistry::get('SkillsWorks')->find();
        $data = $this->request->data();

        $organization_id = Hash::get($data, 'organization_id', null);
        $min_age = Hash::get($data, 'min_age', null);
        $max_age = Hash::get($data, 'max_age', null);
        $sex = Hash::get($data, 'sex', null);
        $users = TableRegistry::get('Users')->find()
                ->select('Users.id');

        $userSelected = false;

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
            $works = TableRegistry::get('Works')->find()
                    ->where(['Works.user_id IN' => $users])
                    ->select('Works.id');

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

}
