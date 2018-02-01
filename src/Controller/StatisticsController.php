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
        $this->loadComponent('SearchSession', ['actions' => ['skills']]);
    }

    public function index() {
        
    }

    public function skills() {

        $organizations = $this->_getOrganizations();
        $junles = $this->_getJunles();

        $this->users = $this->_getUsers();
        $this->works = $this->_getWorks();
        $skills = $this->_getSkills();

        $this->set('users',$this->users);
        $this->set('works',$this->works);
        $this->set(compact('organizations', 'junles', 'skills'));
    }

    protected function _getUsers() {
        $keys = [
            'organization_id',
            'min_age',
            'max_age',
            'sex'
        ];

        $searchParams = [];

        foreach ($keys as $key) {
            $searchParams[$key] = $this->request->data($key);
        }

        $users = TableRegistry::get('Users')
                ->find('search', ['search' => $searchParams])
                ->where(['group_id'=>Defines::GROUP_ENGINEER]);
        
        return $users;
    }
    
    protected function _getWorks(){
        $searchParams = [
            'junle_id'=>$this->request->data('junle_id')
        ];
        
        $users = $this->users->cleanCopy()->select('Users.id');
        
        $works = TableRegistry::get('Works')
                ->find('search',['search'=>$searchParams])
                ->where(['Works.user_id IN'=>$users]);
        
        return $works;
    }

    protected function _getOrganizations() {
        $loginUserId = $this->Auth->user('id');
        $loginUserGroup = $this->Auth->user('group_id');
        $tableO = TableRegistry::get('Organizations');

        //検索フォーム用データ
        $organizations = $tableO->find()
                ->find('pathName')
                ->select($tableO->aliasField('id'))
                ->find('list', ['keyField' => 'id', 'valueField' => 'path']);

        if ($loginUserGroup == Defines::GROUP_MARKER || $loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN) {
            $organizations->find('user', ['user_id' => $loginUserId, 'relation' => 'children']);
        }

        return $organizations;
    }

    protected function _getJunles() {
        $tableJ = TableRegistry::get('Junles');
        $junles = $tableJ->find('list');

        return $junles;
    }

    protected function _getSkills() {
        $Skills = TableRegistry::get('Skills');

        $works = $this->works->cleanCopy()
                ->select('id');
        $query = $Skills->find()
                ->contain('Fields')
                ->join([
                    'table' => 'skills_works',
                    'alias' => 'SkillsWorks',
                    'type' => 'right',
                    'conditions' => 'SkillsWorks.skill_id = Skills.id'
                ])
                ->join([
                    'table' => 'works',
                    'alias' => 'Works',
                    'type' => 'right',
                    'conditions' => ['SkillsWorks.work_id = Works.id','SkillsWorks.user_id <> Works.user_id']
                ])
                ->where(['SkillsWorks.work_id IN'=>$works])
                ->group('Skills.id')
                ->find('fieldPath')
                ->select(['count' => 'count(SkillsWorks.level)'])
                ->select(['average' => 'avg(SkillsWorks.level)'])
                ->select($Skills)
                ->select($Skills->Fields)
                ->order(['Fields.lft' => 'ASC', 'Skills.id' => 'ASC']);
        for ($l = 1; $l <= Defines::SKILL_LEVEL_MAX; $l++) {
            $label = "count_{$l}";
            $value = "count(SkillsWorks.level = {$l} or null)";
            $query
                    ->select([$label => $value]);
        }
        return $query;
    }

}
