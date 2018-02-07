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
    }

    public function initialize() {
        parent::initialize();
        $this->loadComponent('DummyData');
        $this->loadComponent('RequestHandler');
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
        $mode = $this->_getCurrentMode();

        if (isset($user_id)) {
            $user = $tableU->getSessionData($user_id);
            $user->mode = $mode;
            $this->Auth->setUser($user);
            $this->redirect(['controller' => 'home', 'action' => 'index']);
        }

        $users = $tableU->find()
                ->contain(['Groups', 'Organizations']);

        $this->set('users', $users);
    }

    public function test() {
    }
    
    public function test2(){}

    public function upload() {
        $this->viewBuilder()->className('Json');
        // シリアライズする必要があるビュー変数をセットする
        $this->set('data', ['い','ろ','は','に']);
        // JsonView がシリアライズするべきビュー変数を指定する
        $this->set('_serialize', ['data']);
    }
    public function upload1() {
        $this->viewBuilder()->layout(false);
        // シリアライズする必要があるビュー変数をセットする
        $this->set('data', ['い','ろ','は','に']);
        // JsonView がシリアライズするべきビュー変数を指定する
        $this->render('/Common/json');
    }

}
