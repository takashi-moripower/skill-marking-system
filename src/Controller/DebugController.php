<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Organization;
use App\Model\Entity\User;
use App\Model\Entity\Junle;
use App\Defines\Defines;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class DebugController extends AppController {

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('bootstrap');
    }

    public function index() {
        
    }

    public function createDummyOrganizations() {

        $tableO = TableRegistry::get('Organizations');

        for ($i = 1; $i <= 10; $i++) {
            $newEntity = new Organization;
            $newEntity->name = sprintf('組織%02d', $i);
            $tableO->save($newEntity);
        }

        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateOrganizations() {
        $tableO = TableRegistry::get('Organizations');
        $tableO->connection()->query("TRUNCATE organizations");
        $tableO->connection()->query("TRUNCATE organizations_users");

        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyUsers() {
        $tableO = TableRegistry::get('Organizations');
        $organizations = $tableO->find()->toArray();

        $tableU = TableRegistry::get('Users');

        $admin = new User;
        $admin->name = 'T.Abe';
        $admin->group_id = Defines::GROUP_ADMIN;
        $admin->email = 'takashi@moripower.jp';
        $admin->password = '0123';

        foreach ($organizations as $org) {
            $newEntity = new User;
            $newEntity->name = sprintf('組織%02d管理者', $org->id);
            $newEntity->group_id = Defines::GROUP_ORGANIZATION_ADMIN;
            $newEntity->email = sprintf('admin@org%02d.com', $org->id);
            $newEntity->organizations = [$org];
            $newEntity->password = '0123';

            $tableU->save($newEntity);
        }

        foreach ($organizations as $org) {
            $newEntity = new User;
            $newEntity->name = sprintf('組織%02d採点者', $org->id);
            $newEntity->group_id = Defines::GROUP_MARKER;
            $newEntity->email = sprintf('marker@org%02d.com', $org->id);
            $newEntity->organizations = [$org];
            $newEntity->password = '0123';

            $tableU->save($newEntity);
        }

        foreach ($organizations as $org) {
            for ($i = 1; $i <= 10; $i++) {
                $newEntity = new User;
                $newEntity->name = sprintf('組織%02d技術者%02d', $org->id, $i);
                $newEntity->group_id = Defines::GROUP_ENGINEER;
                $newEntity->email = sprintf('engineer%02d@org%02d.com', $i, $org->id);
                $newEntity->organizations = [$org];
                $newEntity->password = '0123';

                $tableU->save($newEntity);
            }
        }

        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateUsers() {
        $tableU = TableRegistry::get('Users');
        $tableU->connection()->query("TRUNCATE users");
        $tableU->connection()->query("TRUNCATE organizations_users");

        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyJunles() {
        $tableJ = TableRegistry::get('Junles');
        
        for($i=1;$i<=10;$i++){
            $newEntity = new Junle;
            $newEntity->name = sprintf('ジャンル%02d',$i);
            
            $tableJ->save($newEntity);
        }
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }
    
    public function truncateJunles() {
        $tableU = TableRegistry::get('Junles');
        $tableU->connection()->query("TRUNCATE junles");
        $tableU->connection()->query("TRUNCATE junles_works");

        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }    

}
