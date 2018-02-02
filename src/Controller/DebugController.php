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
        $root = \Cake\Core\Configure::read('App.wwwRoot');
        $upload = $root . 'uploads/';
        $this->set('data', \Cake\Core\Configure::read('App.wwwRoot'));

        $tmp_dir = ini_get('upload_tmp_dir');
        if ($this->request->is(['POST'])) {
            $data = $this->request->data();
            $this->set('data', $data);
            if ($data['file']['error'] == UPLOAD_ERR_OK) {

                $Files = TableRegistry::get('Files');
                $newFile = $Files->newEntity($data['file']);
                $newFile->work_id = 1;
                $saveResult = $Files->save($newFile);
                if( !$saveResult ){
                    debug( $newFile );exit;
                }
            } else {
                
            }
        }
    }

    public function test4() {
        echo ini_get('upload_tmp_dir');
        exit;
    }

    public function test3() {
        $data = $this->request->data();
        $this->set('data', $data);

        if ($this->request->is(['POST'])) {

            if ($data['file']['error'] == UPLOAD_ERR_OK) {
                $src = $data['file']['tmp_name'];
                $dest = '/home/moripower4/skill/webroot/uploads/0000';
                copy($src, $dest);
            } else {
                
            }
        }

        $this->render('test');
    }

    public function test2() {
        $this->autoRender = false;

        $img = file_get_contents('/home/moripower4/skill/webroot/uploads/0000');

        $this->response->type('zip');
        $this->response->body($img);
    }

}
