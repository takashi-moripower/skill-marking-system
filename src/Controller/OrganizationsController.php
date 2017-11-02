<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Utility\Hash;

/**
 * Organizations Controller
 *
 * @property \App\Model\Table\OrganizationsTable $Organizations
 *
 * @method \App\Model\Entity\Organization[] paginate($object = null, array $settings = [])
 */
class OrganizationsController extends AppController {

    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $user = $this->Auth->user();
        $group = Hash::get($user, 'group_id', 0);

        $this->paginate = [
            'contain' => ['ParentOrganizations'],
            'order' => ['lft' => 'ASC',]
        ];

        $query = $this->Organizations->find('depth')
                ->find('countUsers')
                ->select( $this->Organizations )
        ;
        if ($group == Defines::GROUP_ORGANIZATION_ADMIN) {
            $query->find('user', ['user_id' => $user->id, 'relation' => 'children']);
        }

        $organizations = $this->paginate($query);

        $this->set(compact('organizations'));
        $this->set('_serialize', ['organizations']);
        $this->viewBuilder()->layout('bootstrap');
    }

    /**
     * View method
     *
     * @param string|null $id Organization id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $organization = $this->Organizations->get($id, [
            'contain' => ['ParentOrganizations', 'Users', 'Fields', 'ChildOrganizations', 'OrganizationsUsers']
        ]);

        $this->set('organization', $organization);
        $this->set('_serialize', ['organization']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $user = $this->Auth->user();
        $group = Hash::get($user, 'group_id', 0);

        $organization = $this->Organizations->newEntity();
        if ($this->request->is('post')) {
            $organization = $this->Organizations->patchEntity($organization, $this->request->getData());
            if ($this->Organizations->save($organization)) {
                $this->Flash->success(__('The organization has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The organization could not be saved. Please, try again.'));
        }

        $parentOrganizations = $this->Organizations->ParentOrganizations->find('list', ['limit' => 200]);

        if ($group == Defines::GROUP_ORGANIZATION_ADMIN) {
            $parentOrganizations->find('user', ['user_id' => $user->id, 'relation' => 'children']);
        }

        $this->set(compact('organization', 'parentOrganizations'));
        $this->set('_serialize', ['organization']);
        $this->viewBuilder()->layout('bootstrap');
        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Organization id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $user = $this->Auth->user();
        $group = Hash::get($user, 'group_id', 0);


        $organization = $this->Organizations->get($id, [
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $organization = $this->Organizations->patchEntity($organization, $this->request->getData());
            if ($this->Organizations->save($organization)) {
                $this->Flash->success(__('The organization has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The organization could not be saved. Please, try again.'));
        }

        $parentOrganizations = $this->Organizations->ParentOrganizations->find('list', ['limit' => 200]);
        $parentOrganizations->where([$this->Organizations->ParentOrganizations->aliasField('id') . ' is not' => $organization->id]);

        if ($group == Defines::GROUP_ORGANIZATION_ADMIN) {
            $parentOrganizations->find('user', ['user_id' => $user->id, 'relation' => 'children']);
        }


        $users = $this->Organizations->Users->find('list', ['limit' => 200]);
        $this->set(compact('organization', 'parentOrganizations', 'users'));
        $this->set('_serialize', ['organization']);
        $this->viewBuilder()->layout('bootstrap');
    }

    /**
     * Delete method
     *
     * @param string|null $id Organization id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $organization = $this->Organizations->get($id);

        if ($organization->rght - $organization->lft > 1) {
            $this->Flash->error('下位組織を持つ組織は削除できません');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->Organizations->delete($organization)) {
            $this->Flash->success(__('The organization has been deleted.'));
        } else {
            $this->Flash->error(__('The organization could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
