<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Defines\Defines;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;

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

    public function initialize() {
        parent::initialize();
        $this->viewBuilder()->layout('bootstrap');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $user = $this->Auth->user();
        $group = Hash::get($user, 'group_id', 0);

        $this->paginate = [
            'order' => ['lft' => 'ASC',]
        ];


        $query = $this->Organizations
                ->find('pathName')
                ->find('countUsers')
                ->select($this->Organizations);

        $query
                ->select(['deletable' => $query->newExpr()->addCase([
                        $query->newExpr()->add(['Organizations.parent_id IS' => NULL]),
                        $query->newExpr()->add(['(' . $this->Organizations->aliasField('rght') . '-' . $this->Organizations->aliasField('lft') . ') >' => 2]),
                            ], [false, false, true], ['boolean', 'boolean', 'boolean']
                    )
        ]);



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

        $parentOrganizations = $this->Organizations->ParentOrganizations
                ->find('pathName')
                ->select('id')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);

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

        $parentOrganizations = $this->Organizations->ParentOrganizations
                ->find('pathName')
                ->select('id')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);

        //GROUP_ADMINは自身の管轄下の組織以外を親にできない
        if ($group == Defines::GROUP_ORGANIZATION_ADMIN) {
            $parentOrganizations->find('user', ['user_id' => $user->id, 'relation' => 'children']);
        }

        //組織は自身の子の子になることはできない
        $children = $this->Organizations
                ->find('descendants', ['id' => $id])
                ->select($this->Organizations->aliasField('id'));
        $parentOrganizations
                ->where([$this->Organizations->ParentOrganizations->aliasField('id') . ' NOT IN' => $children]);


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

        if ($organization->parent_id) {
            $this->Organizations->transferMember($id, $organization->parent_id);
        } else {
            if ($this->Auth->user('goup_id') != Defines::GROUP_ADMIN) {
                $this->Flash->error('最上位組織は削除できません');
                return $this->redirect(['action' => 'index']);
            }
        }

        if ($this->Organizations->delete($organization)) {
            $this->Flash->success(__('The organization has been deleted.'));
        } else {
            $this->Flash->error(__('The organization could not be deleted. Please, try again.'));
        }


        return $this->redirect(['action' => 'index']);
    }

    public function setMembers($id) {

        $loginUserId = $this->Auth->user('id');

        $organization = $this->Organizations->get($id, ['contain' => 'Users']);

        if ($this->request->is(['post', 'put', 'patch'])) {
            $organization = $this->Organizations->patchEntity($organization, $this->request->data);
            if ($this->Organizations->save($organization)) {
                $this->Flash->success('メンバーは正常に変更されました');
            }
        }

        $orgs = $this->Organizations->find('user', ['user_id' => $loginUserId, 'relation' => 'children'])
                ->select('id');

        $users = $this->Organizations->Users
                ->find('members', ['organization_id' => $orgs])
                ->find('list');

        $this->set(compact('organization', 'users'));
    }

}
