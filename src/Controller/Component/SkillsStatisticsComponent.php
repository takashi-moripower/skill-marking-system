<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

class SkillsStatisticsComponent extends StatisticsComponent {


    public function skills() {

        $organizations = $this->_getOrganizations();
        $junles = $this->_getJunles();
        $fields = $this->_getFields();

        $this->users = $this->_getUsers();
        $this->works = $this->_getWorks();
        $skills = $this->_getSkills();

        $controller = $this->_registry->getController();

        $controller->set('users', $this->users);
        $controller->set('works', $this->works);
        $controller->set(compact('organizations', 'junles', 'fields', 'skills'));
    }

    protected function _getUsers() {
        $keys = [
            'organization_id',
            'min_age',
            'max_age',
            'sex'
        ];

        $searchParams = [];

        foreach ($keys as $key) {
            $searchParams[$key] = $this->request->data($key);
        }

        $users = TableRegistry::get('Users')
                ->find()
                ->contain('Engineers')
                ->find('search', ['search' => $searchParams])
                ->where(['group_id' => Defines::GROUP_ENGINEER]);

        return $users;
    }

    protected function _getWorks() {
        $searchParams = [
            'junle_id' => $this->request->data('junle_id')
        ];

        $users = $this->users->cleanCopy()->select('Users.id');

        $works = TableRegistry::get('Works')
                ->find('search', ['search' => $searchParams])
                ->where(['Works.user_id IN' => $users]);

        return $works;
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
