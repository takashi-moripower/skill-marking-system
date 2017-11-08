<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('UserEdit');

        $this->viewBuilder()->layout('bootstrap');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'add']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Groups']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login() {
        $this->viewBuilder()->layout('bootstrap');

        $this->Auth->logout();
        if (!$this->request->is('post')) {
            return;
        }

        $user = $this->Auth->identify();

        if (!$user) {
            $this->Flash->error(__('Invalid email address or password, try again'));
            return;
        }

        return $this->_setLoginUser($user['id']);
    }

    protected function _setLoginUser($user_id) {

        $user = $this->Users->getSessionData($user_id);
        $this->Auth->setUser($user);
        $this->redirect(['controller' => 'home', 'action' => 'index']);
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function edit($id) {
        $user = $this->Users->get($id, ['contain' => 'Groups']);
        return $this->UserEdit->edit($user);
    }

    public function add() {
        $user = $this->Users->newEntity();
        return $this->UserEdit->edit($user);
    }

    public function editSelf() {
        $loginUserId = $this->Auth->user('id');
        $user = $this->Users->get($loginUserId, ['contain' => 'Groups']);
        return $this->UserEdit->edit($user);
    }

}
