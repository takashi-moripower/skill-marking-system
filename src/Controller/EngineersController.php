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

    public function initialize() {
        parent::initialize();


        $this->loadComponent('SearchSession', ['actions'=>['index','indexByCondition']]);
        $this->loadComponent('UserEdit');
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

        $tableU = TableRegistry::get('Users');
        $tableO = TableRegistry::get('Organizations');

        $this->paginate = [
            'order' => ['id' => 'ASC'],
        ];

        $query = $tableU
                ->find()
                ->contain(['Engineers', 'Organizations'])
                ->where(['group_id' => Defines::GROUP_ENGINEER])
                ->find('search', ['search' => $this->request->data]);

        if ($loginUserGroup != Defines::GROUP_ADMIN) {
            $query->find('editable', ['user_id' => $loginUserId]);
        }

        $users = $this->paginate($query);


        //検索フォーム用
        $skills = [];
        $skillsQuery = $tableU->works->skills->find('usable', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->find('fieldPath')
                ->select('Skills.name')
                ->select('Skills.id')
                ->toArray();

        foreach ($skillsQuery as $skill) {
            $skills[$skill->id] = $skill->field_path . " > " . $skill->name;
        }


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

    public function indexByCondition() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $tableU = TableRegistry::get('Users');
        $tableO = TableRegistry::get('Organizations');

        $this->paginate = [
            'order' => ['id' => 'ASC'],
        ];

        $query = $tableU
                ->find()
                ->contain(['Engineers', 'Organizations'])
                ->where(['group_id' => Defines::GROUP_ENGINEER])
                ->find('search', ['search' => $this->request->data]);

        if ($loginUserGroup != Defines::GROUP_ADMIN) {
            $query->find('editable', ['user_id' => $loginUserId]);
        }

        $users = $this->paginate($query);
        $this->set(compact('users'));
    }

    public function view($user_id) {
        return $this->_view($user_id);
    }

    public function viewSelf() {
        $loginUserId = $this->Auth->user('id');
        $tableU = TableRegistry::get('Users');
        $users = $tableU
                ->find()
                ->contain(['Engineers', 'Organizations'])
                ->where(['Users.id' => $loginUserId]);

        $this->set('users', $users);
    }

    protected function _view($id) {
        $tableU = TableRegistry::get('users');

        $user = $tableU->find()
                ->where(['id' => $id])
                ->contain(['Organizations', 'Works' => ['Skills']])
                ->first();

        $this->set(compact('user'));
        $this->render('view');
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
        $user = $tableU->get($user_id, ['contain' => ['Organizations', 'Engineers']]);
        return $this->UserEdit->edit($user);
    }

    public function editSelf() {
        $loginUserId = $this->Auth->user('id');

        return $this->redirect(['action' => 'edit', $loginUserId]);
    }

}
