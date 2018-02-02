<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Defines\Defines;
use App\Utility\MyUtil;
use App\Utility\Statistics;

/**
 * Works Controller
 *
 * @property \App\Model\Table\WorksTable $Works
 *
 * @method \App\Model\Entity\Work[] paginate($object = null, array $settings = [])
 */
class StatisticsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('SearchSession', ['actions' => ['skills', 'conditions']]);
        $this->loadComponent('Skills', ['className' => 'SkillsStatistics']);
        $this->loadComponent('Conditions', ['className' => 'ConditionsStatistics']);
    }

    public function index() {
        
    }

    public function skills() {
        $this->Skills->skills();
    }

    public function conditions() {
        $this->Conditions->conditions();
    }
}
