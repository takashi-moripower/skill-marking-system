<?php

namespace TakashiMoripower\AclManager\Controller;

use App\Controller\AppController as BaseController;
use Cake\Event\Event;

class AppController extends BaseController {

    public function beforeFilter(Event $event) {
        $e = $this->eventManager()->dispatch(new Event('beforeAccessControl', NULL, NULl));
        
        if ($e->isStopped()) {
            $this->Auth->deny();
        } else {
            $this->Auth->allow();
        }
        
        //debug
        $this->Auth->allow();
    }

}
