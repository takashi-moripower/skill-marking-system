<?php

namespace App\Utility;

use DateTime;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
class MyUtil {

    public static function dateFormat($dateString) {
        $date = DateTime::createFromFormat('Y-m-d', $dateString);
        if ($date) {
            return $date->format(Defines::DATE_FRMAT);
        } else {
            return Defines::DATE_UNDEFINED;
        }
    }

    public static function strip_tags($str) {
        return strip_tags($str, Defines::ALLOW_TAGS);
    }

    public static function ageFormat($age) {
        if (isset($age)) {
            return "{$age}歳";
        } else {
            return "制限なし";
        }
    }

    public static function getAges($empty = '制限なし') {
        if (isset($empty)) {
            $result = ['' => $empty];
        } else {
            $result = [];
        }
        foreach (range(Defines::CONDITION_AGE_RANGE_MIN, Defines::CONDITION_AGE_RANGE_MAX) as $age) {
            $result[$age] = $age . '歳';
        }
        return $result;
    }

    static function getSkillLevels() {
        return array_combine(range(1, Defines::SKILL_LEVEL_MAX), range(1, Defines::SKILL_LEVEL_MAX));
    }

    /*
     * [3,4] を　12　に
     */

    static function array2flags($levelsArray) {
        $result = 0;
        foreach ($levelsArray as $l) {
            $result += pow(2, $l - 1);
        }

        return $result;
    }

    /*
     * 12  を　[3,4]に
     */

    static function flags2Array($levelsFlags) {
        return array_filter(self::getSkillLevels(), function($l) use($levelsFlags) {
            return (1 << ($l - 1)) & $levelsFlags;
        });
    }

    /**
     * スキルselect用optionsを生成
     * @param type $skills
     * @return type
     */
    public static function toPathList($skills) {
        $skills->find('fieldPath')
                ->contain(['Fields' => ['fields' => []]])
                ->order('Fields.lft')
                ->select(['id', 'name']);

        $list = [];
        foreach ($skills as $skill) {
            $list[$skill->id] = $skill->label;
        }
        return $list;
    }

    public static function getDeviation($skill_id, $organization_id, $level) {


        $query = TableRegistry::get('SkillsWorks')
                ->find();
        if ($organization_id) {
            $users = TableRegistry::get('Users')->find('RootOrganization', ['organization_id' => $organization_id])
                    ->select('Users.id');
            $works = TableRegistry::get('Works')->find()
                    ->where(['Works.user_id IN' => $users])
                    ->select('Works.id');
            $query
                    ->where(['SkillsWorks.work_id IN' => $works]);
        }

        $levels = $query
                ->where(['SkillsWorks.skill_id' => $skill_id])
                ->select(['avg' => 'avg(SkillsWorks.level)'])
                ->select(['std' => 'std(SkillsWorks.level)'])
                ->first();

        if ($levels->std == 0) {
            return 50;
        }

        $dev = ($level - $levels->avg) * 10 / $levels->std + 50;

        return $dev;
    }
    
    public static function countSkills( $skills ){
        
        $result = [];
        foreach( $skills as $skill ){
            $path = "{$skill->id}.{$skill->level}";
            $count = Hash::get($result,$path,0);
            
            $result = Hash::insert($result,$path,$count+1);
        }
        
        return $result;
    }

    public static function first_key( $array ){
        reset( $array );
        return each( $array )['key'];
    }
}
