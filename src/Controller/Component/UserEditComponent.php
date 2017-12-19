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
        $controller = $this->getController();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $user = $this->Users->patchEntity($user, $this->request->getData() ,['associated' => ['Engineers','Organizations']]);
            
debug($user);
            if ($this->Users->save($user)) {
                $controller->Flash->success(__('The user has been saved.'));
            } else {
                $controller->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }

        $loginUser = $controller->Auth->user();

        $organizations = TableRegistry::get('Organizations')
                ->find('pathName')
                ->select('id')
                ->order('Organizations.lft')
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);

        if ($loginUser->group_id != Defines::GROUP_ADMIN) {
            $organizations
                    ->find('user', ['user_id' => $loginUser->id, 'relation' => 'children']);
        }
        
        $groups = TableRegistry::get('Groups')->find('list');
        
        if ($loginUser->group_id != Defines::GROUP_ADMIN) {
            $groups->where(['id >=' => $loginUser->group_id]);
        }


        $controller->set(compact('user', 'organizations' , 'groups'));
        $controller->render('edit');
    }

}
