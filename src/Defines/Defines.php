<?php

namespace App\Defines;

class Defines {
    
    const VERSION = 0.71;

    const MODE_MARKING = 1;     //スキル認定システム
    const MODE_MATCHING = 2;    //人材マッチングシステム
    const TITLES = [
        self::MODE_MARKING => "スキル認定システム",
        self::MODE_MATCHING => "人材マッチングシステム"
    ];
    const ALLOW_TAGS = '<br><p><img><a><div><strong><em><ul><ol><li><iframe>';
    const DATE_FRMAT = 'Y年m月d日';
    const DATE_UNDEFINED = '未定';
    
    
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
        self::MARK_STATE_MARKED => '採点済',
        self::MARK_STATE_UNMARKED => '未採点'
    ];
    const SEX_MALE = 1;
    const SEX_FEMALE = 2;
    const SEX_INDIFFARENCE = 0;
    const CONDITION_SEX_OPTIONS = [
        self::SEX_INDIFFARENCE => '性別不問',
        self::SEX_MALE => '男性のみ',
        self::SEX_FEMALE => '女性のみ'
    ];
    const USERS_SEX = [
        self::SEX_MALE => '男性',
        self::SEX_FEMALE => '女性'
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
    const NAV_ID_CONDITIONS = 10;
    const NAV_ID_CONTACTS = 11;
    const NAV_ID_STATISTICS = 12;
    const NAV_ID_ENG_WORKS = 101;
    const NAV_ID_ENG_NEW_WORK = 102;
    const NAV_ID_ENG_MARKS = 103;
    const NAV_ID_ENG_PROFILE = 104;
    const NAV_ID_ENG_VIEW = 105;
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
        self::NAV_ID_CONDITIONS => ['label' => '人材募集', 'url' => ['controller' => 'Conditions', 'action' => 'index']],
        self::NAV_ID_ENGINEERS => ['label' => '学生一覧', 'url' => ['controller' => 'Engineers', 'action' => 'index']],
        self::NAV_ID_CONTACTS => ['label' => 'コンタクト', 'url' => ['controller' => 'Contacts', 'action' => 'index']],
        self::NAV_ID_STATISTICS => ['label' => '統計', 'url' => ['controller' => 'statistics', 'action' => 'index']],
        self::NAV_ID_ENG_WORKS => ['label' => '作品一覧', 'url' => ['controller' => 'Works', 'action' => 'index']],
        self::NAV_ID_ENG_NEW_WORK => ['label' => '新規投稿', 'url' => ['controller' => 'Works', 'action' => 'add']],
        self::NAV_ID_ENG_PROFILE => ['label' => 'プロファイル', 'url' => ['controller' => 'engineers', 'action' => 'editSelf']],
        self::NAV_ID_ENG_VIEW => ['label' => 'スキル評価', 'url' => ['controller' => 'engineers', 'action' => 'viewSelf']],
    ];
    const NAV_GROUP_TEMPLATES = [
        self::MODE_MATCHING => [
            self::GROUP_ADMIN => [
                self::NAV_ID_ORGANIZATIONS,
                self::NAV_ID_USERS,
                self::NAV_ID_FIELDS,
                self::NAV_ID_SKILLS,
                self::NAV_ID_ENGINEERS,
                self::NAV_ID_WORKS,
                self::NAV_ID_GROUPS,
                self::NAV_ID_JUNLES,
                self::NAV_ID_CONDITIONS,
                self::NAV_ID_CONTACTS,
                self::NAV_ID_STATISTICS,
            ],
            self::GROUP_ORGANIZATION_ADMIN => [
                self::NAV_ID_ORGANIZATIONS,
                self::NAV_ID_USERS,
                self::NAV_ID_FIELDS,
                self::NAV_ID_ENGINEERS,
                self::NAV_ID_WORKS,
                self::NAV_ID_CONDITIONS,
                self::NAV_ID_CONTACTS,
                self::NAV_ID_STATISTICS,
            ],
            self::GROUP_MARKER => [
                self::NAV_ID_ENGINEERS,
                self::NAV_ID_WORKS,
                self::NAV_ID_FIELDS,
                self::NAV_ID_CONDITIONS,
                self::NAV_ID_CONTACTS,
                self::NAV_ID_STATISTICS,
            ],
            self::GROUP_ENGINEER => [
                self::NAV_ID_ENG_VIEW,
                self::NAV_ID_ENG_WORKS,
                self::NAV_ID_ENG_NEW_WORK,
                self::NAV_ID_FIELDS,
                self::NAV_ID_CONDITIONS,
                self::NAV_ID_CONTACTS,
            ],
        ],
        self::MODE_MARKING => [
            self::GROUP_ADMIN => [
                self::NAV_ID_ORGANIZATIONS,
                self::NAV_ID_USERS,
                self::NAV_ID_FIELDS,
                self::NAV_ID_SKILLS,
                self::NAV_ID_ENGINEERS,
                self::NAV_ID_WORKS,
                self::NAV_ID_GROUPS,
                self::NAV_ID_JUNLES,
                self::NAV_ID_CONDITIONS,
                self::NAV_ID_CONTACTS,
                self::NAV_ID_STATISTICS,
            ],
            self::GROUP_ORGANIZATION_ADMIN => [
                self::NAV_ID_ORGANIZATIONS,
                self::NAV_ID_USERS,
                self::NAV_ID_FIELDS,
                self::NAV_ID_ENGINEERS,
                self::NAV_ID_WORKS,
                self::NAV_ID_STATISTICS,
            ],
            self::GROUP_MARKER => [
                self::NAV_ID_ENGINEERS,
                self::NAV_ID_WORKS,
                self::NAV_ID_FIELDS,
                self::NAV_ID_STATISTICS,
            ],
            self::GROUP_ENGINEER => [
                self::NAV_ID_ENG_VIEW,
                self::NAV_ID_ENG_WORKS,
                self::NAV_ID_ENG_NEW_WORK,
                self::NAV_ID_FIELDS,
            ],
        ],
    ];
    
    const ACTION_LABEL = [
        'edit'=>'編集',
        'view'=>'閲覧',
        'delete'=>'削除'
    ];
    
    const ENCODING = [
        'SJIS-win',
        'UTF-8',
    ];
    const CONDITION_OPTION_TYPE_MAX_AGE = 1;
    const CONDITION_OPTION_TYPE_MIN_AGE = 2;
    const CONDITION_OPTION_TYPE_SEX = 3;
    const CONDITION_OPTION_TYPE_DATE_START = 4;
    const CONDITION_OPTION_TYPE_DATE_END = 5;
    const CONDITION_OPTION_TYPE_LOCATION = 6;
    const CONDITION_OPTION_TYPE_SKILL = 255;
    const CONDITION_OPTIONS = [
        self::CONDITION_OPTION_TYPE_SKILL => 'スキル',
        self::CONDITION_OPTION_TYPE_SEX => '性別',
        self::CONDITION_OPTION_TYPE_MAX_AGE => '年齢',
        self::CONDITION_OPTION_TYPE_DATE_START => '期間',
        self::CONDITION_OPTION_TYPE_LOCATION => '開催地'
    ];
    const CONDITION_PUBLISHED_TRUE = 1;
    const CONDITION_PUBLISHED_FALSE = 0;
    const CONDITION_PUBLISHED_STATE = [
        self::CONDITION_PUBLISHED_TRUE => '公開',
        self::CONDITION_PUBLISHED_FALSE => '非公開',
    ];
    
    const CONDITION_AGE_RANGE_MAX = 99;
    const CONDITION_AGE_RANGE_MIN = 1;
    const CONDITION_AGE_INDIFFARENCE = null;
    
    const FORM_TEMPLATE_INLINE_CHECKBOX = ['checkboxWrapper' => '<div class="checkbox d-inline-block px-2">{{label}}</div>'];
    const FORM_TEMPLATE_DATE = ['dateWidget' => '{{year}} 年 {{month}} 月 {{day}} 日 '];
    const FORM_TEMPLATE_INLINE_CONTAINER = ['inputContainer' => '<div class="input {{type}}{{required}} d-inline-block">{{content}}</div>'];
    const FORM_TEMPLATE_RADIO = ['nestingLabel' => '{{hidden}}{{input}}<label class="mr-4" {{attrs}}>{{text}}</label>',];
    const FORM_TEMPLATE_RADIO_SKILL_LEVELS = ['nestingLabel' => '{{hidden}}<label class="btn btn-skill-selector" {{attrs}}>{{input}}{{text}}</label>',];
    const CONTACT_FLAG_FROM_ENGINEER = 1;
    const CONTACT_FLAG_FROM_COMPANY = 0x10;
    const CONTACT_FLAG_FROM_TEACHER = 0x100;
    const CONTACT_FLAG_ALLOW_BY_ENGINEER = 0x2;
    const CONTACT_FLAG_ALLOW_BY_COMPANY = 0x20;
    const CONTACT_FLAG_ALLOW_BY_TEACHER = 0x200;
    const CONTACT_FLAG_DENIED_BY_ENGINEER = 0x4;
    const CONTACT_FLAG_DENIED_BY_COMPANY = 0x40;
    const CONTACT_FLAG_DENIED_BY_TEACHER = 0x400;
    const CONTACT_FLAG_FILTER_ENGINEER = 0xf;
    const CONTACT_FLAG_FILTER_COMPANY = 0xf0;
    const CONTACT_FLAG_FILTER_TEACHER = 0xf00;
    const CONTACT_STATE_UNDEFINED = 0;
    const CONTACT_STATE_ALLOW = 1;
    const CONTACT_STATE_DENY = 2;
    const CONTACT_STATES_ENGINEER = [
        self::CONTACT_STATE_UNDEFINED => '未定',
        self::CONTACT_STATE_ALLOW => '応募',
        self::CONTACT_STATE_DENY => '見送',
    ];
    const CONTACT_STATES_TEACHER = [
        self::CONTACT_STATE_UNDEFINED => '未定',
        self::CONTACT_STATE_ALLOW => '承認',
        self::CONTACT_STATE_DENY => '否認',
    ];
    const CONTACT_STATES_COMPANY = [
        self::CONTACT_STATE_UNDEFINED => '未定',
        self::CONTACT_STATE_ALLOW => '勧誘',
        self::CONTACT_STATE_DENY => '見送',
    ];
    const SKILL_DISPLAY_FLAG_DETAILED_OTHERS = 1;
    const SKILL_DISPLAY_FLAG_DETAILED_OWNER = 2;
    const SKILL_DISPLAY_FLAG_DETAILED_VIEWER = 4;
    const SKILL_DISPLAY_FLAG_FOR_ENGINEERS = 0;
    const SKILL_DISPLAY_FLAG_FOR_WORKS = self::SKILL_DISPLAY_FLAG_DETAILED_OWNER & self::SKILL_DISPLAY_FLAG_DETAILED_VIEWER;

    
    //  ユーザー登録前のEmail確認処理の猶予時間　分単位
    const USER_REGISTRY_TOKEN_LIMIT = 30;   
    const USER_REGISTRY_MAIL_TITLE = self::TITLES[self::MODE_MARKING]."登録手続き";
    
    
    
    
    const OPTION_KEY_COMPANY_DISPLAYING = 'COMPANY_DISPLAYING';
}
