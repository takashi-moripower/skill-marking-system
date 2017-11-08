<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use App\Defines\Defines;

class UserEditComponent extends Component {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->Users = TableRegistry::get('Users');
    }

    public function edit($user) {
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        
        $controller = $this->getController();
        $loginUser = $controller->Auth->user();

        $organizations = TableRegistry::get('Organizations')
                ->find('pathName')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);
        
        if( $loginUser->group_id != Defines::GROUP_ADMIN){
            $organizations
                    ->find('user', ['user_id' => $loginUser->id, 'relation' => 'children']);
        }                
        

        $controller->set(compact('user', 'organizations'));
        $controller->render('edit');
    }

}
