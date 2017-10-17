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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $user = $this->Auth->user();
        $group = Hash::get($user, 'group_id', 0);


        $this->paginate = [
            'contain' => ['Fields' => ['Organizations']]
        ];

        $query = $this->Skills->find();

        if ($group == Defines::GROUP_ORGANIZATION_ADMIN) {
            $query->find('usable', ['user_id' => $user->id, 'group_id' => $user->group_id]);
        }
        $skills = $this->paginate($query);


        $this->set(compact('skills', 'orgs'));
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
        $fields = $this->Skills->Fields
                ->find('editable', ['user_id' => $user->id])
                ->find('list', ['limit' => 200]);

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
        $fields = $this->Skills->Fields
                ->find('editable', ['user_id' => $user->id])
                ->find('list', ['limit' => 200]);

        $this->set(compact('skill', 'fields'));
        $this->set('_serialize', ['skill']);
        $this->viewBuilder()->layout('bootstrap');
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