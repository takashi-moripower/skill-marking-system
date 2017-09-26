<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\SkillsWork;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
use Cake\Utility\Hash;
use App\Defines\Defines;

/**
 * Works Controller
 *
 * @property \App\Model\Table\WorksTable $Works
 *
 * @method \App\Model\Entity\Work[] paginate($object = null, array $settings = [])
 */
class WorksController extends AppController {
    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Search.Prg', [
            'emptyValues' => [
                'mark-state' => 0
            ]
        ]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');

        $this->paginate = [
            'order' => ['id' => 'ASC'],
            'contain' => [
                'Users' => ['fields' => ['id', 'name']],
                'Junles' => ['sort' => ['Junles.id' => 'ASC']],
                'Skills' => ['sort' => ['SkillsWorks.level' => 'DESC'], 'conditions' => [], 'fields' => ['name', 'SkillsWorks.level', 'SkillsWorks.work_id']],
            ]
        ];

        $query = $this->Works
                ->find('user', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->find('search', ['search' => $this->request->query]);



        $works = $this->paginate($query);

        //検索フォーム用データ
        $organizations = $this->Works->Users->Organizations
                ->find('list', ['keyField' => 'id', 'valueField' => 'name'])
        ;

        if ($loginUserGroup == Defines::GROUP_MARKER || $loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN) {
            $organizations
                    ->leftJoin(['OU' => 'organizations_users'], 'OU.organization_id = Organizations.id')
                    ->where(['OU.user_id' => $loginUserId]);
        }

        $organizations = ['' => 'すべて'] + $organizations->toArray();

        $junles = ['' => 'すべて'] + $this->Works->Junles
                        ->find('list', ['keyField' => 'id', 'valueField' => 'name'])
                        ->toArray();

        $this->set(compact('works', 'organizations', 'junles'));
        $this->viewBuilder()->layout('bootstrap');
    }

    /**
     * View method
     *
     * @param string|null $id Work id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $work = $this->Works->get($id, [
            'contain' => ['Users', 'Junles', 'Skills', 'Files']
        ]);

        $this->set('work', $work);
        $this->set('_serialize', ['work']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Work id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $work = $this->Works->get($id);
        if ($this->Works->delete($work)) {
            $this->Flash->success(__('The work has been deleted.'));
        } else {
            $this->Flash->error(__('The work could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function mark($workId) {
        if ($this->request->is('POST')) {
            $this->_postMark($workId);
        }

        $markerId = $this->Auth->user('id');

        $work = $this->Works->find()
                ->where(['Works.id' => $workId])
                ->contain([
                    'Users' => ['fields' => ['Users.id', 'name']],
                    'Skills' => ['sort' => ['SkillsWorks.level' => 'DESC'], 'conditions' => [], 'fields' => ['id', 'name', 'SkillsWorks.level', 'SkillsWorks.work_id']],
                    'Files',
                ])
                ->first();

        $creatorId = $work->user_id;

        $skillsUsed = Hash::extract($work, "skills.{n}.id");

        $skillsToSet = $this->Works->Skills
                        ->find('usable', ['user_ids' => [$markerId, $creatorId]])
                        ->where([$this->Works->Skills->aliasField('id') . ' NOT IN' => $skillsUsed])
                        ->find('list', ['keyField' => 'id', 'valueField' => 'name']);

        $skillsToSet = ['0' => '-'] + $skillsToSet->toArray();


        $this->set(compact('work', 'skillsToSet'));
        $this->viewBuilder()->layout('bootstrap');
    }

    protected function _postMark($workId) {
        $tableSW = TableRegistry::get('SkillsWorks');
        $data = $this->request->data();
        $action = $data['action'];

        $param = [
            'work_id' => $data['work_id'],
            'skill_id' => $data['skill_id'],
            'user_id' => $data['user_id'],
        ];

        if ($action == 'delete') {
            $tableSW->deleteAll($param);
        }

        if ($action == 'set') {
            if (!$tableSW->exists($param)) {
                $entity = new SkillsWork($param);
            } else {
                $entity = $tableSW->find()
                        ->where($param)
                        ->first();
            }

            $entity->level = $data['level'];

            $tableSW->save($entity);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Work id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id) {
        $work = $this->Works->find()
                ->where(['Works.id' => $id])
                ->contain([
                    'Users' => ['fields' => ['Users.id', 'name']],
                    'Files',
                    'Junles' => ['sort' => ['Junles.id' => 'ASC']],
                ])
                ->first();

        $junles = $this->Works->Junles->find('list');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $work = $this->Works->patchEntity($work, $this->request->data);

            if ($this->Works->save($work)) {
                $this->Flash->success(__('作品情報は正常に保存されました'));
                return $this->redirect(['controller' => 'works', 'action' => 'index']);
            } else {
                $this->Flash->error('保存に失敗');
            }
        }

        $this->set(compact('work', 'junles'));
        $this->viewBuilder()->layout('bootstrap');
    }

    public function add() {
        $work = $this->Works->newEntity([
            'junles' => [],
            'files' => [],
            'user_id' => $this->Auth->user('id')
        ]);

        return $this->_edit($work);
    }

    protected function _edit($work) {

        $junles = $this->Works->Junles->find('list');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $work = $this->Works->patchEntity($work, $this->request->data);

            if ($this->Works->save($work)) {
                $this->Flash->success(__('作品情報は正常に保存されました'));
                return $this->redirect(['controller' => 'works', 'action' => 'index']);
            } else {
                $this->Flash->error('保存に失敗');
            }
        }

        $this->set(compact('work', 'junles'));
        $this->viewBuilder()->layout('bootstrap');
        $this->render('edit');
    }

}
