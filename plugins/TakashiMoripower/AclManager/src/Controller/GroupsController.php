<?php

namespace TakashiMoripower\AclManager\Controller;

use TakashiMoripower\AclManager\Controller\AppController;
use Cake\Event\Event;
use JcPires\AclManager\Event\PermissionsEditor;

/**
 * Groups Controller
 *
 * @property \TakashiMoripower\AclManager\Model\Table\GroupsTable $Groups
 */
class GroupsController extends AppController {

    public function beforeRender(Event $event) {
        $this->viewBuilder()->layout('acl_groups');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $groups = $this->paginate($this->Groups);

        $this->set(compact('groups'));
        $this->set('_serialize', ['groups']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $group = $this->Groups->newEntity();
        if ($this->request->is('post')) {
            $group = $this->Groups->patchEntity($group, $this->request->data);
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('The group has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The group could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('group'));
        $this->set('_serialize', ['group']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Group id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $group = $this->Groups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->patchEntity($group, $this->request->data);
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('The group has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The group could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('group'));
        $this->set('_serialize', ['group']);
    }

    public function permit() {
        if ($this->request->is(['post', 'put', 'patch'])) {
            $this->eventManager()->on(new PermissionsEditor());

            foreach ($this->request->data as $group_id => $data) {
                $group = $this->Groups->get($group_id);
                $perms = new Event('Permissions.editPerms', $this, [
                    'Aro' => $group,
                    'datas' => $data
                ]);
                $this->eventManager()->dispatch($perms);
            }
        }


        $this->loadComponent('JcPires/AclManager.AclManager');
        $paths = $this->AclManager->getFormActions();

        $groups = $this->Groups->find()
                ->all();

        $this->set(compact('paths', 'groups'));
    }

    public function update() {
        $this->loadComponent('JcPires/AclManager.AclManager');
        $this->AclManager->acosBuilder();

        $this->Flash->success('actions updated');
        $this->redirect(['action' => 'index']);
    }

    public function format() {
        if (!$this->request->is(['post'])) {
            return;
        }

        if ($this->request->data['code'] != $this->request->data['code2']) {
            $this->Flash->error('初期化コードが違います');
            return;
        }

        $this->Groups->connection()->query("TRUNCATE acos");
        $this->Groups->connection()->query("TRUNCATE aros_acos");
        $this->Flash->success('アクションリストは初期化されました');

        $this->loadComponent('JcPires/AclManager.AclManager');
        $this->AclManager->acosBuilder();

        return $this->redirect(['action' => 'index']);
    }

}
