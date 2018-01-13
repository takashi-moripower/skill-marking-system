<?php

namespace App\Utility;

use DateTime;
use App\Defines\Defines;

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

    public static function getAges() {
        $result = ['' => '制限なし'];
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

}
