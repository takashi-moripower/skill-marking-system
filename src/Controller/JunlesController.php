<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Junles Controller
 *
 * @property \App\Model\Table\JunlesTable $Junles
 *
 * @method \App\Model\Entity\Junle[] paginate($object = null, array $settings = [])
 */
class JunlesController extends AppController {
    public $helpers = [
        'Paginator' => ['templates' => 'paginator-templates']
    ];

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
       
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $junles = $this->paginate($this->Junles);

        $this->set(compact('junles'));
        $this->set('_serialize', ['junles']);
    }

    /**
     * View method
     *
     * @param string|null $id Junle id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $junle = $this->Junles->get($id, [
            'contain' => ['Works']
        ]);

        $this->set('junle', $junle);
        $this->set('_serialize', ['junle']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $junle = $this->Junles->newEntity();
        if ($this->request->is('post')) {
            $junle = $this->Junles->patchEntity($junle, $this->request->getData());
            if ($this->Junles->save($junle)) {
                $this->Flash->success(__('The junle has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The junle could not be saved. Please, try again.'));
        }
        $works = $this->Junles->Works->find('list', ['limit' => 200]);
        $this->set(compact('junle', 'works'));
        $this->set('_serialize', ['junle']);
        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Junle id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $junle = $this->Junles->get($id, [
            'contain' => ['Works']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $junle = $this->Junles->patchEntity($junle, $this->request->getData());
            if ($this->Junles->save($junle)) {
                $this->Flash->success(__('The junle has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The junle could not be saved. Please, try again.'));
        }
        $works = $this->Junles->Works->find('list', ['limit' => 200]);
        $this->set(compact('junle', 'works'));
        $this->set('_serialize', ['junle']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Junle id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $junle = $this->Junles->get($id);
        if ($this->Junles->delete($junle)) {
            $this->Flash->success(__('The junle has been deleted.'));
        } else {
            $this->Flash->error(__('The junle could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
