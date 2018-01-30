<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utility;

use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class Statistics {

    public $SkillsWorks;

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
        $skill_ids = $this->getQuery()
                ->select('SkillsWorks.skill_id')
                ->group('SkillsWorks.skill_id');

        $skills = TableRegistry::get('Skills')
                ->find()
                ->contain('Fields')
                ->where(['Skills.id IN' => $skill_ids])
                ->order(['Fields.lft' => 'ASC', 'Skills.id' => 'ASC']);
        return $skills;
    }

    public function count($skill_id, $level) {
        $query = $this->getQuery();

        if ($skill_id) {
            $query->where(['skill_id' => $skill_id]);
        }

        if ($level) {
            $query->where(['level' => $level]);
        }

        $count = $query->count();

        return $count;
    }

    public function average($skill_id) {
        $query = $this->getQuery();

        $query->where(['skill_id' => $skill_id]);

        $avg = $query->avg('level');
        return $avg;
    }

}
