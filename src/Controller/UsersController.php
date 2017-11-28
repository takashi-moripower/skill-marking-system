<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use \SplFileObject;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {

    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

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
            'contain' => ['Groups', 'Organizations']
        ];

        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $users = $this->Users->find();

        if ($loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN) {
            $users->find('editable', ['user_id' => $loginUserId]);
        }

        $users = $this->paginate($users);


        $this->set(compact('users', 'organizations'));
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
        if ($this->Users->beforeDelete($id) && $this->Users->delete($user)) {
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
        $user = $this->Users->get($id, ['contain' => ['Groups', 'Organizations']]);
        $this->UserEdit->edit($user);

        return;
    }

    public function add() {
        $user = $this->Users->newEntity();
        $this->UserEdit->edit($user);
        return;
    }

    public function editSelf() {
        $loginUserId = $this->Auth->user('id');
        $user = $this->Users->get($loginUserId, ['contain' => ['Groups', 'Organizations']]);

        $this->UserEdit->edit($user);

        return;
    }

    public function import() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $Orgs = TableRegistry::get('Organizations');
        $Users = TableRegistry::get('Users');

        $orgs = $Orgs
                ->find('pathName')
                ->select('id')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);
        if ($loginUserGroup != Defines::GROUP_ADMIN) {
            $orgs->find('user', ['relation' => 'children', 'user_id' => $loginUserId]);
        }

        $this->set('organizations', $orgs);

        if ($this->request->is('post')) {

            $encode = Defines::ENCODING[$this->request->data['encode']];

            $file = new SplFileObject($this->request->data['file']['tmp_name']);
            $file->setFlags(SplFileObject::READ_CSV);

            $results = [
                'success' => 0,
                'failed' => 0,
            ];
            foreach ($file as $line) {

                mb_convert_variables('UTF-8', $encode, $line);

                if (is_array($line) && count($line) >= 3) {

                    $newEntity = $this->Users->newEntity([
                        'name' => $line[0],
                        'email' => $line[1],
                        'password' => $line[2],
                        'group_id' => Defines::GROUP_ENGINEER,
                        'organizations' => ['_ids' => [$this->request->data['organization_id']]]
                    ]);

                    if ($this->Users->save($newEntity)) {
                        $results['success'] ++;
                    } else {
                        $results['failed'] ++;
                    }
                } else {
                    $results['failed'] ++;
                }
            }
            $this->Flash->success(sprintf('成功　%02d　件 / 失敗　%02d　件', $results['success'], $results['failed']));

            $this->set('results', $results);
        }
    }

}
