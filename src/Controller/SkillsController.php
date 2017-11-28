<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;

/**
 * Skills Controller
 *
 * @property \App\Model\Table\SkillsTable $Skills
 *
 * @method \App\Model\Entity\Skill[] paginate($object = null, array $settings = [])
 */
class SkillsController extends AppController {

    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('SearchSession', []);
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
            'contain' => ['Fields' => ['Organizations']]
        ];


        $query = $this->Skills->find('fieldPath')
                ->select(['Skills.id', 'Skills.name' , 'Skills.note' , 'Fields.organization_id'])
                ->select(['org_name' => $this->Skills->Fields->Organizations->aliasField('name')])
                ->find('search', ['search' => $this->request->data]);

        if ($loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN) {
            $query->find('usable', ['user_id' => $loginUserId, 'group_id' => $loginUserGroup]);
        }

        $skillsEditable = TableRegistry::get('SkillsEditable', ['table' => 'skills', 'className' => '\App\Model\Table\SkillsTable'])
                ->find('editable', ['user_id' => $loginUserId])
                ->select('id');

        $query
                ->select(['editable' => $query->newExpr()->addCase([
                        $query->newExpr()->add(['Skills.id IN' => $skillsEditable]),
                        1,
                        'boolean'
                    ])
        ]);


        $skills = $this->paginate($query);

        //検索用

        $fields = $this->Skills->Fields->find('pathName')
                ->select($this->Skills->Fields->aliasField('id'))
                ->find('list', ['keyField' => 'id', 'valueField' => 'path'])
                ->find('usable', ['user_id' => $loginUserId])
                ->order('Fields.lft');
        
        $organizations = $this->Skills->Fields->Organizations->find('pathName')
                ->find('list',['keyField'=>'id','valueField'=>'path'])
                ->select('Organizations.id')
                ->order('Organizations.lft');
        
        if ($loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN) {
            $organizations->find('user',['user_id'=>$loginUserId,'relation'=>'children']);
        }



        $this->set(compact('skills', 'fields','organizations'));
        $this->set('_serialize', ['skills']);
        $this->viewBuilder()->layout('bootstrap');
    }

    /**
     * View method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $skill = $this->Skills->get($id, [
            'contain' => ['Fields', 'Works']
        ]);

        $this->set('skill', $skill);
        $this->set('_serialize', ['skill']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $user = $this->Auth->user();

        $skill = $this->Skills->newEntity();
        if ($this->request->is('post')) {
            $skill = $this->Skills->patchEntity($skill, $this->request->getData());
            if ($this->Skills->save($skill)) {
                $this->Flash->success(__('The skill has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The skill could not be saved. Please, try again.'));
        }
        $fields = $this->_getFieldsForEdit($user->id);

        $this->set(compact('skill', 'fields'));
        $this->set('_serialize', ['skill']);
        $this->viewBuilder()->layout('bootstrap');
        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $user = $this->Auth->user();

        $skill = $this->Skills->get($id, [
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $skill = $this->Skills->patchEntity($skill, $this->request->getData());
            if ($this->Skills->save($skill)) {
                $this->Flash->success(__('The skill has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The skill could not be saved. Please, try again.'));
        }
        $fields = $this->_getFieldsForEdit($user->id);

        $this->set(compact('skill', 'fields'));
        $this->set('_serialize', ['skill']);
        $this->viewBuilder()->layout('bootstrap');
    }

    protected function _getFieldsForEdit($user_id) {
        $fields = $this->Skills->Fields
                ->find('editable', ['user_id' => $user_id]);

        $list = [];
        foreach ($fields as $field) {
            $list[$field->id] = $field->path;
        }

        return $list;
    }

    /**
     * Delete method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $skill = $this->Skills->get($id);
        if ($this->Skills->delete($skill)) {

            $tableSW = TableRegistry::get('skills_works');
            $tableSW->find()
                    ->delete()
                    ->where(['skill_id' => $id])
                    ->execute();

            $this->Flash->success(__('The skill has been deleted.'));
        } else {
            $this->Flash->error(__('The skill could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
