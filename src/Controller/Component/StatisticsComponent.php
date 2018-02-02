<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;

class StatisticsComponent extends Component {

    public $components = ['Auth'];
    public $users;
    public $works;

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
                    'conditions' => ['SkillsWorks.work_id = Works.id', 'SkillsWorks.user_id <> Works.user_id']
                ])
                ->where(['SkillsWorks.work_id IN' => $works])
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

        $field_id = $this->request->data('field_id');
        if (!empty($field_id)) {
            $query->leftJoin(['RootFields' => 'fields'], ["RootFields.id" => $field_id])
                    ->where(['RootFields.lft <= Fields.lft', 'RootFields.rght >= Fields.rght'])
            ;
        }

        return $query;
    }


    protected function _getFields() {
        $loginUserId = $this->Auth->user('id');
        $tableF = TableRegistry::get('Fields');
        $fields = $tableF->find('pathName')
                ->select($tableF->aliasField('id'))
                ->find('list', ['keyField' => 'id', 'valueField' => 'path'])
                ->find('usable', ['user_id' => $loginUserId])
                ->order('Fields.lft');
        return $fields;
    }
    
    protected function _getJunles() {
        $tableJ = TableRegistry::get('Junles');
        $junles = $tableJ->find('list');

        return $junles;
    }

}
