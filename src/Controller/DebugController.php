<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

class DebugController extends AppController {

    protected $_junles = null;

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('bootstrap');
    }

    public function initialize() {
        parent::initialize();
        $this->loadComponent('DummyData');
    }

    public function index() {
        
    }

    public function truncateOrganizations() {
        $this->DummyData->truncateOrganizations();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyOrganizations() {
        $this->DummyData->createDummyOrganizations();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateUsers() {
        $this->DummyData->truncateUsers();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyUsers() {
        $this->DummyData->createDummyUsers();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateJunles() {
        $this->DummyData->truncateJunles();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyJunles() {
        $this->DummyData->createDummyJunles();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateFields() {
        $this->DummyData->truncateFields();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyFields() {
        $this->DummyData->createDummyFields();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateSkills() {
        $this->DummyData->truncateSkills();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummySkills() {
        $this->DummyData->createDummySkills();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateWorks() {
        $this->DummyData->truncateWorks();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyWorks() {
        $this->DummyData->createDummyWorks();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateMarks() {
        $this->DummyData->truncateMarks();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyMarks() {
        $this->DummyData->createDummyMarks();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function loginAs($user_id = null) {
        $tableU = TableRegistry::get('Users');
        if (isset($user_id)) {
            $user = $tableU->getSessionData($user_id);
            $this->Auth->setUser($user);
            $this->redirect(['controller' => 'home', 'action' => 'index']);
        }


        $users = $tableU->find()
                ->contain(['Groups', 'Organizations']);

        $this->set('users', $users);
    }

    public function test() {
        $user_id = $this->Auth->user('id');

        $tableU = TableRegistry::get('users');
        $tableS = TableRegistry::get('skills');
        $tableW = TableRegistry::get('Works');
        $tableSW = TableRegistry::get('skills_works');
        $tableO = TableRegistry::get('Organizations');
        $tableF = TableRegistry::get('Fields');


        $query = $tableW
                ->find()
                ->select(['id','name'])
                ->count();
   
//        $data = $query->toArray();
        $data = $query;

        $this->set('data', $data);
        $this->render('/Common/debug');
    }

}
