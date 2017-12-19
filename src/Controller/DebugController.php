<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

class DebugController extends AppController {

    protected $_junles = null;

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    public function initialize() {
        parent::initialize();
        $this->loadComponent('DummyData');
    }

    public function index() {
        
    }

    public function truncateOrganizations() {
        $this->DummyData->truncateOrganizations();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyOrganizations() {
        $this->DummyData->createDummyOrganizations();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateUsers() {
        $this->DummyData->truncateUsers();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyUsers() {
        $this->DummyData->createDummyUsers();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateJunles() {
        $this->DummyData->truncateJunles();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyJunles() {
        $this->DummyData->createDummyJunles();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateFields() {
        $this->DummyData->truncateFields();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyFields() {
        $this->DummyData->createDummyFields();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateSkills() {
        $this->DummyData->truncateSkills();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummySkills() {
        $this->DummyData->createDummySkills();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateWorks() {
        $this->DummyData->truncateWorks();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyWorks() {
        $this->DummyData->createDummyWorks();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function truncateMarks() {
        $this->DummyData->truncateMarks();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function createDummyMarks() {
        $this->DummyData->createDummyMarks();
        return $this->redirect(['controller' => 'debug', 'action' => 'index']);
    }

    public function loginAs($user_id = null) {
        $tableU = TableRegistry::get('Users');
        if (isset($user_id)) {
            $user = $tableU->getSessionData($user_id);
            $this->Auth->setUser($user);
            $this->redirect(['controller' => 'home', 'action' => 'index']);
        }


        $users = $tableU->find()
                ->contain(['Groups', 'Organizations']);

        $this->set('users', $users);
    }

    public function test() {
        $user_id = $this->Auth->user('id');

        $tableU = TableRegistry::get('Users');
        $tableS = TableRegistry::get('skills');
        $tableW = TableRegistry::get('Works');
        $tableSW = TableRegistry::get('skills_works');
        $tableO = TableRegistry::get('Organizations');
        $tableF = TableRegistry::get('Fields');
        $tableC = TableRegistry::get('Conditions');
        $tableCS = TableRegistry::get('ConditionsSkills');

        $user_id = 4;
        /*
        $skillsMatches = $tableCS->find()
                ->select([
                    'condition_id' => 'ConditionsSkills.condition_id',
                    'count' => 'count(DISTINCT ConditionsSkills.id)'
                ])
                ->join([
                    'SkillsWorks' => [
                        'table' => 'skills_works',
                        'type' => 'inner',
                        'conditions' => [
                            'SkillsWorks.skill_id =' . $tableCS->aliasField('skill_id'),
                            '( POWER( 2 , SkillsWorks.level-1) & ConditionsSkills.levels ) <> 0'
                        ]
                    ],
                    'Works' => [
                        'table' => 'works',
                        'type' => 'inner',
                        'conditions' => [
                            'SkillsWorks.work_id = Works.id',
                            'Works.user_id' => $user_id,
                            'Works.user_id <> SkillsWorks.user_id'
                        ]
                    ]
                ])
                ->group('ConditionsSkills.condition_id');

        $skillsAll = $tableCS->find()
                ->select([
                    'condition_id' => 'ConditionsSkills.condition_id',
                    'count' => 'count(DISTINCT ConditionsSkills.id)'
                ])
                ->group('ConditionsSkills.condition_id');

        $query = $tableC->find()

                ->join([
            'skills_matches' => [
                'table' => $skillsMatches,
                'type' => 'inner',
                'conditions' => [
                    'skills_matches.condition_id = Conditions.id'
                ]
            ],
            'skills_all' => [
                'table' => $skillsAll,
                'type' => 'inner',
                'conditions' => [
                    'skills_all.condition_id = Conditions.id'
                ]
            ]
                ]
        )
                ->where(['skills_matches.count = skills_all.count']);
*/
        
        $query = $tableC->find('user',['user_id'=>4]);

        $data = $query->toArray();

        $this->set('data', $data);
        $this->render('/Common/debug');
    }

}
