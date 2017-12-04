<?php

namespace App\Defines;

class Defines {
    const TITLE = "スキル認定システム";
    const TITLE2 = "人材マッチングシステム";
    
    /*
     * 数値設定
     */

    const SKILL_LEVEL_MAX = 4;

    /*
     * グループID
     */
    const GROUP_ADMIN = 1;
    const GROUP_ORGANIZATION_ADMIN = 2;
    const GROUP_MARKER = 3;
    const GROUP_ENGINEER = 4;

    /*
     * 選択肢
     */
    const MARK_STATE_ALL = 0;
    const MARK_STATE_MARKED = 1;
    const MARK_STATE_UNMARKED = 2;
    const MARK_STATES = [
        self::MARK_STATE_ALL => 'すべて',
        self::MARK_STATE_MARKED => '採点済',
        self::MARK_STATE_UNMARKED => '未採点'
    ];
    
    const SEX_MALE = 1;
    const SEX_FEMALE = 2;
    const SEX_INDIFFARENCE = 0;
    
    const CONDITIONS_SEX = [
        self::SEX_INDIFFARENCE => '性別不問',
        self::SEX_MALE => '男性のみ',
        self::SEX_FEMALE => '女性のみ'
    ];
    
    const USERS_SEX = [
        self::SEX_MALE =>'男性',
        self::SEX_FEMALE =>'女性'
    ];

    /*
     * 添付ファイル
     * 画像として扱う拡張子
     */
    const IMAGE_EXTRACTS = [
        'bmp',
        'gif',
        'jpg',
        'jpeg',
        'png'
    ];

    /**
     * ナビ
     */
    const NAV_ID_HOME = 0;
    const NAV_ID_USERS = 1;
    const NAV_ID_GROUPS = 2;
    const NAV_ID_ORGANIZATIONS = 3;
    const NAV_ID_FIELDS = 4;
    const NAV_ID_SKILLS = 5;
    const NAV_ID_JUNLES = 6;
    const NAV_ID_WORKS = 7;
    const NAV_ID_ENGINEERS = 8;
    const NAV_ID_PROFILE = 9;
    const NAV_ID_ENG_WORKS = 101;
    const NAV_ID_ENG_NEW_WORK = 102;
    const NAV_ID_ENG_MARKS = 103;
    const NAV_ID_ENG_PROFILE = 104;
    
    const NAV_TEMPLATES = [
        self::NAV_ID_HOME => ['label' => 'Home', 'url' => ['controller' => 'Home', 'action' => 'index']],
        self::NAV_ID_USERS => ['label' => 'ユーザ', 'url' => ['controller' => 'users', 'action' => 'index']],
        self::NAV_ID_GROUPS => ['label' => '権限', 'url' => ['plugin' => 'TakashiMoripower/AclManager', 'controller' => 'groups', 'action' => 'index']],
        self::NAV_ID_ORGANIZATIONS => ['label' => '組織', 'url' => ['controller' => 'Organizations', 'action' => 'index']],
        self::NAV_ID_FIELDS => ['label' => 'スキル分野', 'url' => ['controller' => 'Fields', 'action' => 'index']],
        self::NAV_ID_SKILLS => ['label' => 'スキル', 'url' => ['controller' => 'Skills', 'action' => 'index']],
        self::NAV_ID_JUNLES => ['label' => 'ジャンル', 'url' => ['controller' => 'Junles', 'action' => 'index']],
        self::NAV_ID_WORKS => ['label' => '作品一覧', 'url' => ['controller' => 'Works', 'action' => 'index']],
        self::NAV_ID_PROFILE => ['label' => 'プロファイル', 'url' => ['controller' => 'Users', 'action' => 'editSelf']],
        self::NAV_ID_ENGINEERS => ['label' => '学生一覧', 'url' => ['controller' => 'Engineers', 'action' => 'index']],
        self::NAV_ID_ENG_WORKS => ['label' => '作品一覧', 'url' => ['controller' => 'Works', 'action' => 'index']],
        self::NAV_ID_ENG_NEW_WORK => ['label' => '新規投稿', 'url' => ['controller' => 'Works', 'action' => 'add']],
        self::NAV_ID_ENG_MARKS => ['label' => '評価', 'url' => ['controller' => 'home', 'action' => 'index']],
        self::NAV_ID_ENG_PROFILE => ['label'=>'プロファイル' , 'url'=>['controller'=>'engineers','action'=>'editSelf']],
    ];
    const NAV_GROUP_TEMPLATES = [
        self::GROUP_ADMIN => [
            self::NAV_ID_ORGANIZATIONS,
            self::NAV_ID_USERS,
            self::NAV_ID_FIELDS,
            self::NAV_ID_SKILLS,
            self::NAV_ID_ENGINEERS,
            self::NAV_ID_WORKS,
            self::NAV_ID_PROFILE,
            
            self::NAV_ID_GROUPS,
            self::NAV_ID_JUNLES,
        ],
        self::GROUP_ORGANIZATION_ADMIN => [

            self::NAV_ID_ORGANIZATIONS,
            self::NAV_ID_USERS,
            self::NAV_ID_FIELDS,
            self::NAV_ID_SKILLS,
            self::NAV_ID_ENGINEERS,
            self::NAV_ID_WORKS,
            self::NAV_ID_PROFILE,
        ],
        self::GROUP_MARKER => [
            self::NAV_ID_ENGINEERS,
            self::NAV_ID_WORKS,
            self::NAV_ID_PROFILE,
        ],
        self::GROUP_ENGINEER => [
            self::NAV_ID_ENG_WORKS,
            self::NAV_ID_ENG_NEW_WORK,
            self::NAV_ID_ENG_PROFILE,
//            self::NAV_ID_ENG_MARKS
        ],
    ];
    
    const ENCODING = [
        'SJIS-win',
        'UTF-8',
    ];

}
