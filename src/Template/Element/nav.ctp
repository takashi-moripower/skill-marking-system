<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$group_id = $this->getLoginUser('group_id');

$NAV_TEMPLATES = [
    Defines::GROUP_ADMIN => [
        'Home' => ['controller' => 'Home', 'action' => 'index'],
        'Users' => ['controller' => 'users', 'action' => 'index'],
        'Groups' => ['plugin' => 'TakashiMoripower/AclManager', 'controller' => 'groups', 'action' => 'index'],
        'Organizations' => ['controller' => 'Organizations', 'action' => 'index'],
        'Fields' => ['controller' => 'Fields', 'action' => 'index'],
        'Skills' => ['controller' => 'Skills', 'action' => 'index'],
        'Junles' => ['controller' => 'Junles', 'action' => 'index'],
        'Works' => ['controller' => 'Works', 'action' => 'index'],
        'Engineers'=>['controller'=>'Engineers','action'=>'index'],
    ],
    Defines::GROUP_MARKER => [
        'Home' => ['controller' => 'Home', 'action' => 'index'],
        'Works' => ['controller' => 'Works', 'action' => 'index'],
    ],
    Defines::GROUP_ENGINEER =>[
        'Home' => ['controller' => 'Home', 'action' => 'index'],
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
                    <?php foreach ($navs as $lavel => $url): ?>
                        <?php
                        if (Hash::get($url, 'controller') == $this->name) {
                            $class = "nav-link active";
                        } else {
                            $class = "nav-link";
                        }
                        ?>
                        <li class="nav-item">
                            <?= $this->Html->link($lavel, $url, ['class' => $class]) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
