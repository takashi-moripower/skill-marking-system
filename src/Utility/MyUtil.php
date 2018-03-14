<?php

namespace App\Utility;

use DateTime;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Security;
use Cake\Core\Configure;

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

    public static function first_key($array) {
        reset($array);
        return each($array)['key'];
    }

    /**
     * ランダム文字列生成 (英数字)
     * $length: 生成する文字数
     */
    public static function makeRandStr($length) {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $r_str = null;
        for ($i = 0; $i < $length; $i++) {
            $r_str .= $str[rand(0, count($str) - 1)];
        }
        return $r_str;
    }

    public static function encypt($str) {
        $key = Configure::read('key', '12345678901234567980123456789012');
        $salt = Configure::read('salt', 'salt');

        return Security::encrypt($str, $key, $salt);
    }

    public static function decypt($str) {
        $key = Configure::read('key', '12345678901234567980123456789012');
        $salt = Configure::read('salt', 'salt');
        return Security::decrypt($str, $key, $salt);
    }

}
