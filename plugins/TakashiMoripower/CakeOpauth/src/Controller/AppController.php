<?php

namespace TakashiMoripower\CakeOpauth\Controller;

use App\Controller\AppController as BaseController;
use Cake\Event\Event;

class AppController extends BaseController
{
	public function beforeFilter(Event $event) {
		// ACL無効化
		$this->Auth->allow();
	}
}
