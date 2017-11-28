<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\I18n\Time;

class TimeStampHelper extends Helper {

    public function timestamp($filePath) {
        $localPath = realpath(WWW_ROOT . $filePath);

        if (!file_exists($localPath)) {
            return null;
        }
        
        
        $TS = filemtime($localPath);
        $t = Time::createFromTimestamp($TS);

        return $t->format('YmdHi');
    }

}
