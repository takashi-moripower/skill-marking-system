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
        $this->loadComponent('SearchSession', []);
        $this->loadComponent('UserEdit');
        $this->viewBuilder()->layout('bootstrap');
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
        $tableO = TableRegistry::get('Organizations');

        $this->paginate = [
            'order' => ['id' => 'ASC'],
        ];

        $query = $tableU
                ->find()
                ->where(['group_id' => Defines::GROUP_ENGINEER])
                ->find('search', ['search' => $this->request->data]);

        $users = $this->paginate($query);


        //検索フォーム用
        $skills = [];
        $skillQuery = $tableU->works->skills->find('usable', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->contain('Fields')
                ->order(['Fields.lft' => 'ASC', 'Skills.id' => 'ASC']);
        foreach ($skillQuery as $skill) {
            $skills[$skill->id] = $skill->path . " > " . $skill->name;
        }

        $skills = [0 => '-'] + $skills;

        $levels = array_combine(range(1, Defines::SKILL_LEVEL_MAX), range(1, Defines::SKILL_LEVEL_MAX));

        //検索フォーム用データ

        $organizations = $tableO->find()
                ->find('pathName')
                ->select($tableO->aliasField('id'))
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);

        if ($loginUserGroup == Defines::GROUP_MARKER || $loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN) {
            $organizations->find('user', ['user_id' => $loginUserId, 'relation' => 'children']);
        }



        $this->set(compact('users', 'skills', 'levels', 'organizations'));
    }

    public function view($user_id) {
        $tableU = TableRegistry::get('users');

        $user = $tableU->find()
                ->where(['id' => $user_id])
                ->contain(['Organizations', 'Works' => ['Skills']])
                ->first();

        $this->set(compact('user'));
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
        $this->render('edit');
    }

    public function edit($user_id) {
        $tableU = TableRegistry::get('Users');
        $user = $tableU->get($user_id, ['contain' => 'organizations']);
        return $this->UserEdit->edit($user);
    }

    public function editSelf() {
        $loginUserId = $this->Auth->user('id');

        return $this->redirect(['action' => 'edit', $loginUserId]);
    }

}
