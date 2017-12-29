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

    public function initialize() {
        parent::initialize();

        $this->LoadComponent('SearchSession', []);
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
                'Skills' => ['sort' => ['SkillsWorks.level' => 'DESC'], 'finder' => ['fieldPath'], 'fields' => ['id', 'name', 'field_id', 'SkillsWorks.level', 'SkillsWorks.work_id']],
            ]
        ];

        $query = $this->Works
                ->find('mark')
                ->find('user', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
                ->find('search', ['search' => $this->request->data])
                ->group($this->Works->aliasField('id'))
                ->select($this->Works);

        $works = $this->paginate($query);

        //検索フォーム用データ
        $organizations = $this->Works->Users->Organizations
                ->find('PathName')
                ->select('id')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);

        if ($loginUserGroup == Defines::GROUP_MARKER || $loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN) {
            $organizations->find('user', ['user_id' => $loginUserId, 'relation' => 'children']);
        }

        $organizations;

        $junles = ['' => 'すべて'] + $this->Works->Junles
                        ->find('list', ['keyField' => 'id', 'valueField' => 'name'])
                        ->toArray();

        $this->set(compact('works', 'organizations', 'junles'));
       
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
            'contain' => [
                'Users',
                'Junles',
                'Files',
                'Skills' => ['sort' => ['SkillsWorks.level' => 'DESC'], 'conditions' => [], 'fields' => ['id', 'name', 'field_id', 'SkillsWorks.level', 'SkillsWorks.work_id']],
            ]
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
        $this->request->allowMethod(['post', 'delete', 'get']);
        $work = $this->Works->get($id);
        if ($this->Works->delete($work)) {
            $this->Flash->success(__('The work has been deleted.'));

            $tableJW = TableRegistry::get('junles_works');
            $tableJW->find()
                    ->delete()
                    ->where(['work_id' => $id])
                    ->execute();

            $tableSW = TableRegistry::get('skills_works');
            $tableSW->find()
                    ->delete()
                    ->where(['work_id' => $id])
                    ->execute();

            $tableF = TableRegistry::get('files');
            $tableF->find()
                    ->delete()
                    ->where(['work_id' => $id])
                    ->execute();
        } else {
            $this->Flash->error(__('The work could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function mark($workId) {
        if ($this->request->is('POST')) {
            $this->_postMark($workId);
        }
        $loginUserId = $this->Auth->user('id');

        $tableSW = TableRegistry::get('skills_works');
        $tableS = TableRegistry::get('skills');

        $markerId = $this->Auth->user('id');

        $work = $this->Works->find()
                ->where(['Works.id' => $workId])
                ->contain([
                    'Users' => ['fields' => ['Users.id', 'name']],
                    'Files',
                    'Junles',
                    'Comments' => ['Users' => ['fields' => ['name']]],
                    'Skills' => ['sort' => ['SkillsWorks.level' => 'DESC'], 'finder' => ['fieldPath'], 'fields' => ['id', 'name', 'field_id', 'SkillsWorks.level', 'SkillsWorks.work_id']],
                ])
                ->first();

        $skillsUsed = Hash::extract($work->getSkillsBy($loginUserId)->toArray(), '{n}.id');

        $skillsUnUsed = $tableS->find('usable',['user_id'=>$loginUserId]);
        
        if (!empty($skillsUsed)) {
            $skillsUnUsed
                    ->where([$tableS->aliasField('id') . ' not IN ' => $skillsUsed]);
        }

        $skillsToSet = \App\Model\Table\SkillsTable::toPathList($skillsUnUsed);
        $this->set(compact('work', 'skillsToSet'));
       
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
       
        $this->render('edit');
    }

}
