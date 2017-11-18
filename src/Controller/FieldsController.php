<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Utility\Hash;

/**
 * Fields Controller
 *
 * @property \App\Model\Table\FieldsTable $Fields
 *
 * @method \App\Model\Entity\Field[] paginate($object = null, array $settings = [])
 */
class FieldsController extends AppController {
    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $loginUser = $this->Auth->user();

        $this->paginate = [
            'contain' => ['Organizations', 'ParentFields'],
            'order' => ['lft' => 'ASC',]
        ];

        $query = $this->Fields
                ->find('editable', ['user_id' => $loginUser->id])
                ->find('countSkills')
                ->find('depth')
                ->select($this->Fields)
                ->select($this->Fields->Organizations)
        ;

        $fields = $this->paginate($query);

        $this->set(compact('fields'));
        $this->set('_serialize', ['fields']);
        $this->viewBuilder()->layout('bootstrap');
    }

    /**
     * View method
     *
     * @param string|null $id Field id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $field = $this->Fields->get($id, [
            'contain' => ['Organizations', 'ParentFields', 'ChildFields', 'Skills']
        ]);

        $this->set('field', $field);
        $this->set('_serialize', ['field']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $field = $this->Fields->newEntity();
        return $this->_edit($field);
    }

    /**
     * Edit method
     *
     * @param string|null $id Field id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $field = $this->Fields->get($id);
        return $this->_edit($field);
    }

    protected function _edit($field) {
        $loginUser = $this->Auth->user();


        if ($this->request->is(['patch', 'post', 'put'])) {
            $field = $this->Fields->patchEntity($field, $this->request->getData());
            if ($this->Fields->save($field)) {
                $this->Flash->success(__('The field has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The field could not be saved. Please, try again.'));
        }


        $organizations = $this->Fields->Organizations
                ->find('pathName')
                ->select('id')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path'])
                ->order($this->Fields->Organizations->aliasField('lft'));

        if ($loginUser->group_id == Defines::GROUP_ORGANIZATION_ADMIN) {
            $organizations->find('user', ['user_id' => $loginUser->id]);
        }

        $parentFields = $this->Fields->ParentFields
                ->find('editable', ['user_id' => $loginUser->id])
                ->find('pathName')
                ->select('id')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path'])
                ->order($this->Fields->ParentFields->aliasField('lft'));



        //自身の子孫の子孫になることはできない
        if (!$field->isNew()) {
            $children = $this->Fields->find('descendants', ['id' => $field->id])
                    ->select($this->Fields->aliasField('id'));
            $parentFields
                    ->where([$this->Fields->ParentFields->aliasField('id') . ' not in' => $children]);
        }


        $this->set(compact('field', 'organizations', 'parentFields'));
        $this->set('_serialize', ['field']);
        $this->viewBuilder()->layout('bootstrap');
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id Field id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);

        $field = $this->Fields->get($id);

        if ($field->rght - $field->lft > 1) {
            $this->Flash->error('下位グループを持つグループは削除できません');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->Fields->delete($field)) {
            $this->Flash->success(__('The field has been deleted.'));
        } else {
            $this->Flash->error(__('The field could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
