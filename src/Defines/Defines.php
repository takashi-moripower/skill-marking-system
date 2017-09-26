<?php

namespace App\Defines;

class Defines {

    const GROUP_ADMIN = 1;
    const GROUP_ORGANIZATION_ADMIN = 2;
    const GROUP_MARKER = 3;
    const GROUP_ENGINEER = 4;
    
    const SKILL_LEVEL_MAX = 5;
    
    const MARK_STATE_ALL = 0;
    const MARK_STATE_MARKED = 1;
    const MARK_STATE_UNMARKED = 2;
    
    const MARK_STATES = [
        self::MARK_STATE_ALL => 'すべて',
        self::MARK_STATE_MARKED => '採点済み',
        self::MARK_STATE_UNMARKED => '未採点'
    ];
    
    const IMAGE_EXTRACTS = [
        'bmp',
        'gif',
        'jpg',
        'jpeg',
        'png'
    ];
}
