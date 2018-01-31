<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utility;

use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Defines\Defines;

class Statistics {

    public $SkillsWorks;
    public $countCache = [];

    public function __construct($query = null) {
        if ($query == null) {
            $this->_query = TableRegistry::get('SkillsWorks')
                    ->find();
        } else {
            $this->_query = $query;
        }
    }

    public function getQuery() {
        return $this->_query->cleanCopy();
    }

    
    public function getSkills() {
        $Skills = TableRegistry::get('Skills');
        
        $SWIds = $this->getQuery()
                ->select('id');
        
        $skills = $Skills->find()
                ->contain('Fields')
                ->join([
                    'table' => 'skills_works',
                    'alias' => 'SkillsWorks',
                    'type' => 'right',
                    'conditions' => 'SkillsWorks.skill_id = Skills.id',
                ])
                ->group('Skills.id')
                ->select('SkillsWorks.id')
                ->having(['SkillsWorks.id IN' => $SWIds])
                ->select(['count' => 'count(SkillsWorks.level)'])
                ->select(['average' => 'avg(SkillsWorks.level)'])
                ->select($Skills)
                ->select($Skills->Fields)
                ->order(['Fields.lft' => 'ASC', 'Skills.id' => 'ASC']);

        for ($l = 1; $l <= Defines::SKILL_LEVEL_MAX; $l++) {
            $label = "count_{$l}";
            $value = "count(SkillsWorks.level = {$l} or null)";
            $skills
                    ->select([$label => $value]);
        }

        return $skills;
    }
    
    public static function getColor( $a , $b ){
        
    }

}
