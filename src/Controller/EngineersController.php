<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Model\Entity\User;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class EngineersController extends AppController {

    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Search.Prg', [
        ]);
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['add']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $tableU = TableRegistry::get('users');

        $this->paginate = [
            'order' => ['id' => 'ASC'],
        ];

        $query = $tableU
                ->find()
                ->where(['group_id' => Defines::GROUP_ENGINEER])
                ->find('search', ['search' => $this->request->query]);

        $users = $this->paginate($query);


        //検索フォーム用
        $skills = $tableU->works->skills->find('usable', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->find('list');

        $skills = [0 => '-'] + $skills->toArray();

        $levels = array_combine(range(1, Defines::SKILL_LEVEL_MAX), range(1, Defines::SKILL_LEVEL_MAX));


        $this->set(compact('users', 'skills', 'levels'));
        $this->viewBuilder()->layout('bootstrap');
    }

    public function view($user_id) {
        $tableU = TableRegistry::get('users');

        $user = $tableU->find()
                ->where(['id' => $user_id])
                ->contain(['Organizations', 'Works' => ['Skills']])
                ->first();

        $this->set(compact('user'));
        $this->viewBuilder()->layout('bootstrap');
    }

    public function add() {
        $tableU = TableRegistry::get('users');

        $user = $tableU->newEntity(['group_id' => Defines::GROUP_ENGINEER,]);

        if ($this->request->is('post')) {
            $user = $tableU->patchEntity($user, $this->request->getData());
            if ($tableU->save($user)) {
                $this->Flash->success(__('The engineer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The engineer could not be saved. Please, try again.'));
        }

        $organizations = TableRegistry::get('organizations')
                ->find('user', ['user_id' => $this->Auth->user()->id, 'relation' => 'children'])
                ->find('list');

        $this->set(compact('user', 'organizations'));
        $this->viewBuilder()->layout('bootstrap');
        $this->render('edit');
    }

    public function edit($user_id) {
        $tableU = TableRegistry::get('users');

        $user = $tableU->get($user_id, ['contain' => 'organizations']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $tableU->patchEntity($user, $this->request->getData());
            if ($tableU->save($user)) {
                $this->Flash->success(__('The engineer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The engineer could not be saved. Please, try again.'));
        }

        $organizations = TableRegistry::get('organizations')
                ->find('list');
        if ($this->Auth->user()->group_id != Defines::GROUP_ADMIN) {
            $organizations
            ->find('user', ['user_id' => $this->Auth->user()->id, 'relation' => 'children']);
        }

        $this->set(compact('user', 'organizations'));
        $this->viewBuilder()->layout('bootstrap');
        $this->render('edit');
    }

}
