<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\SkillsWork;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Defines\Defines;
use App\Utility\MyUtil;

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
                'Skills' => ['Fields', 'sort' => ['Fields.lft' => 'ASC', 'Skills.id'], 'finder' => ['fieldPath'], 'fields' => ['id', 'name', 'field_id', 'SkillsWorks.level', 'SkillsWorks.work_id', 'Fields.lft']],
            ]
        ];

        $query = $this->Works
                ->find('search', ['search' => $this->request->data])
                ->find('mark')
                ->find('user', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup])
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
        $work = $this->Works->find()
                ->where(['Works.id' => $workId])
                ->contain([
                    'Users' => 'Organizations',
                    'Junles',
                    'Files'
                ])
                ->first();
        $this->set(compact('work'));
    }

    public function ajaxComments($workId) {
        $this->viewBuilder()->enableAutoLayout(false);

        if ($this->request->is('POST')) {
            $this->_postComment();
        }

        $work = $this->Works->get($workId, ['contain' => ['Comments' => 'Users']]);
        $this->set(compact('work'));
    }

    protected function _postComment() {
        $data = $this->request->data();
        if (empty($data)) {
            return;
        }

        $Comments = $this->loadModel('Comments');

        if (isset($data["id"])) {
            $comment = $Comments->get($data["id"]);
        } else {
            $comment = $Comments->newEntity();
        }

        $Comments->patchEntity($comment, $data);

        //本文未設定or空の場合
        if (!isset($comment->comment) || trim($comment->comment) == '') {
            //ID設定済みなら削除
            if (isset($comment->id)) {
                $Comments->delete($comment);
            }
            //ID未設定なら何もしない
            return;
        }

        $Comments->save($comment);
        return;
    }

    public function ajaxSkills($workId) {
        $this->viewBuilder()->enableAutoLayout(false);

        if ($this->request->is('POST')) {
            $this->_postMark();
        }



        $Skills = $this->loadModel('Skills');
        $loginUserId = $this->Auth->user('id');



        $work = $this->Works->get($workId);

        $creatorId = $work->user_id;

        //作者による評価
        $skillsBySelf = $Skills->find('fieldPath')
                ->select($Skills)
                ->select(['SkillsWorks.level'])
                ->select(['Fields.lft'])
                ->leftJoin(['SkillsWorks' => 'skills_works'], ['Skills.id = SkillsWorks.skill_id'])
                ->leftJoin(['Fields' => 'fields'], ['Skills.field_id = Fields.id'])
                ->order(['Fields.lft' => 'ASC', 'Skills.id' => 'ASC', 'SkillsWorks.level' => 'DESC'])
                ->where(['SkillsWorks.work_id' => $workId, 'SkillsWorks.user_id' => $creatorId]);

        //作者、ログインユーザー以外からの評価
        $skillsByOther = $Skills->find('fieldPath')
                ->select($Skills)
                ->select(['SkillsWorks.level'])
                ->select(['Fields.lft'])
                ->leftJoin(['SkillsWorks' => 'skills_works'], ['Skills.id = SkillsWorks.skill_id'])
                ->leftJoin(['Fields' => 'fields'], ['Skills.field_id = Fields.id'])
                ->order(['Fields.lft' => 'ASC', 'Skills.id' => 'ASC', 'SkillsWorks.level' => 'DESC'])
                ->where(['SkillsWorks.work_id' => $workId, 'SkillsWorks.user_id NOT IN' => [$work->user_id, $loginUserId]]);
        $skillsByOther = \App\Model\Entity\Skill::findMaxLevel($skillsByOther);

        //作者による評価　＝　評価希望項目
        $skillsRequested = $this->loadModel('SkillsWorks')->find()
                ->where(['work_id' => $workId, 'user_id' => $creatorId])
                ->select('Skill_id');

        //ログインユーザーが採点済みの項目
        $skillsMarked = $this->loadModel('SkillsWorks')->find()
                ->where(['work_id' => $workId, 'user_id' => $loginUserId])
                ->select('Skill_id');

        //評価済みor評価希望項目
        $skillsByLoginUser = $Skills->find('fieldPath')
                ->select($Skills)
                ->select(['SkillsWorks.level'])
                ->select(['Fields.lft'])
                ->leftJoin(['SkillsWorks' => 'skills_works'], ['Skills.id = SkillsWorks.skill_id', 'SkillsWorks.work_id' => $workId, 'SkillsWorks.user_id' => $loginUserId])
                ->leftJoin(['Fields' => 'fields'], ['Skills.field_id = Fields.id'])
                ->order(['Fields.lft' => 'ASC', 'Skills.id' => 'ASC', 'SkillsWorks.level' => 'DESC'])
                ->where(['or' => [
                'Skills.id IN' => $skillsRequested,
                'Skills.id in' => $skillsMarked,
        ]]);

        //追加用リスト　ログインユーザーが未使用のスキル
        $skillsUnused = $Skills->find('usable', ['user_id' => $loginUserId])
                ->where(['Skills.id NOT IN' => $skillsMarked]);

        $skillsUnused = MyUtil::toPathList($skillsUnused);



        $this->set(compact('skillsBySelf', 'skillsByOther', 'skillsByLoginUser', 'skillsUnused'));
        $this->set('work', $work);
    }

    protected function _postMark() {
        $tableSW = TableRegistry::get('SkillsWorks');
        $data = $this->request->data();

        if (empty($data)) {
            return;
        }

        $level = $data['level'];

        $param = [
            'work_id' => $data['work_id'],
            'skill_id' => $data['skill_id'],
            'user_id' => $data['user_id'],
        ];

        if ($level == 0) {
            $tableSW->deleteAll($param);
            $this->set('skillUpdated', $data['skill_id']);
            return;
        }


        if (!$tableSW->exists($param)) {
            $entity = new SkillsWork($param);
        } else {
            $entity = $tableSW->find()
                    ->where($param)
                    ->first();
        }
        $entity->level = $level;

        $tableSW->save($entity);

        $this->set('skillUpdated', $data['skill_id']);
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
