<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Fields Controller
 *
 * @property \App\Model\Table\FieldsTable $Fields
 *
 * @method \App\Model\Entity\Field[] paginate($object = null, array $settings = [])
 */
class FieldsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Organizations', 'ParentFields'],
            'order' => ['lft' => 'ASC',]
        ];
        
        $query =$this->Fields
                ->find('countSkills')
                ->find('depth')
                ->select( $this->Fields )
                ->select( $this->Fields->Organizations )
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
        if ($this->request->is('post')) {
            $field = $this->Fields->patchEntity($field, $this->request->getData());
            if ($this->Fields->save($field)) {
                $this->Flash->success(__('The field has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The field could not be saved. Please, try again.'));
        }

        $organizations = $this->Fields->Organizations->find('list', ['limit' => 200]);
        $parentFields = $this->Fields->ParentFields->find('list', ['limit' => 200]);
        $parentFields->where([$this->Fields->ParentFields->aliasField('id') . ' is not' => $field->id]);

        $this->set(compact('field', 'organizations', 'parentFields'));
        $this->set('_serialize', ['field']);
        $this->viewBuilder()->layout('bootstrap');
        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Field id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $field = $this->Fields->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $field = $this->Fields->patchEntity($field, $this->request->getData());
            if ($this->Fields->save($field)) {
                $this->Flash->success(__('The field has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The field could not be saved. Please, try again.'));
        }
        $organizations = $this->Fields->Organizations->find('list', ['limit' => 200]);
        $parentFields = $this->Fields->ParentFields->find('list', ['limit' => 200]);
        $parentFields->where([$this->Fields->ParentFields->aliasField('id') . ' is not' => $field->id]);

        $this->set(compact('field', 'organizations', 'parentFields'));
        $this->set('_serialize', ['field']);
        $this->viewBuilder()->layout('bootstrap');
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
