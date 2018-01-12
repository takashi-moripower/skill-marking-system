<?php

namespace App\Utility;

use DateTime;
use App\Defines\Defines;

class MyUtil {

    public static function dateFormat($dateString) {
        $date = DateTime::createFromFormat('Y-m-d', $dateString);
        if( $date ){
            return $date->format( Defines::DATE_FRMAT );
        }else{
            return Defines::DATE_UNDEFINED;
        }
    }

    public static function strip_tags($str) {
        return strip_tags($str, Defines::ALLOW_TAGS);
    }
}
