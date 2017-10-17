<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$group_id = $this->getLoginUser('group_id');

$NAV_TEMPLATES = [
    Defines::GROUP_ADMIN => [
        Defines::NAV_ID_HOME,
        Defines::NAV_ID_USERS,
        Defines::NAV_ID_GROUPS,
        Defines::NAV_ID_ORGANIZATIONS,
        Defines::NAV_ID_FIELDS,
        Defines::NAV_ID_SKILLS,
        Defines::NAV_ID_JUNLES,
        Defines::NAV_ID_WORKS,
        Defines::NAV_ID_ENGINEERS,
    ],
    Defines::GROUP_ORGANIZATION_ADMIN => [
        Defines::NAV_ID_HOME,
        Defines::NAV_ID_WORKS,
        Defines::NAV_ID_SKILLS,
        Defines::NAV_ID_ORGANIZATIONS,
        Defines::NAV_ID_ENGINEERS,
    ],
    Defines::GROUP_MARKER => [
        Defines::NAV_ID_HOME,
        Defines::NAV_ID_WORKS,
        Defines::NAV_ID_ENGINEERS,
    ],
    Defines::GROUP_ENGINEER =>[
        Defines::NAV_ID_HOME,
        Defines::NAV_ID_WORKS,
    ],
];

$navs = Hash::get($NAV_TEMPLATES, $group_id, []);

if( empty($navs)){
    return;
}
?>
<div class="bg-light mb-3">
    <div class="container">
        <div class="row">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <ul class="nav">
                    <?php foreach ($navs as $nav_id): ?>
                        <?php
                        $label = Hash::get(Defines::NAV_TEMPLATES,"{$nav_id}.label");
                        $url = Hash::get(Defines::NAV_TEMPLATES,"{$nav_id}.url");
                        if (Hash::get($url, 'controller') == $this->name) {
                            $class = "nav-link active";
                        } else {
                            $class = "nav-link";
                        }
                        ?>
                        <li class="nav-item">
                            <?= $this->Html->link($label, $url, ['class' => $class]) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
