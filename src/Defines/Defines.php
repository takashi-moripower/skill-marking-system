<?php

namespace App\Defines;

class Defines {
    /*
     * 数値設定
     */

    const SKILL_LEVEL_MAX = 5;

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
        self::MARK_STATE_MARKED => '採点済み',
        self::MARK_STATE_UNMARKED => '未採点'
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
    const NAV_TEMPLATES = [
        self::NAV_ID_HOME => ['label' => 'Home', 'url' => ['controller' => 'Home', 'action' => 'index']],
        self::NAV_ID_USERS => ['label' => 'ユーザ', 'url' => ['controller' => 'users', 'action' => 'index']],
        self::NAV_ID_GROUPS => ['label' => '権限', 'url' => ['plugin' => 'TakashiMoripower/AclManager', 'controller' => 'groups', 'action' => 'index']],
        self::NAV_ID_ORGANIZATIONS => ['label' => '組織', 'url' => ['controller' => 'Organizations', 'action' => 'index']],
        self::NAV_ID_FIELDS => ['label' => 'スキル分野', 'url' => ['controller' => 'Fields', 'action' => 'index']],
        self::NAV_ID_SKILLS => ['label' => 'スキル', 'url' => ['controller' => 'Skills', 'action' => 'index']],
        self::NAV_ID_JUNLES => ['label' => 'ジャンル', 'url' => ['controller' => 'Junles', 'action' => 'index']],
        self::NAV_ID_WORKS => ['label' => '作品', 'url' => ['controller' => 'Works', 'action' => 'index']],
        self::NAV_ID_ENGINEERS => ['label' => 'クリエイター', 'url' => ['controller' => 'Engineers', 'action' => 'index']],
    ];

}