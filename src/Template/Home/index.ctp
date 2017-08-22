<?php

use Cake\Utility\Hash;

$navs = [
    'Home' => ['controller' => 'Home', 'action' => 'index'],
    'Users' => ['controller' => 'users', 'action' => 'index'],
    'Groups' => ['plugin' => 'TakashiMoripower/AclManager', 'controller' => 'groups', 'action' => 'index'],
    'Organizations' => ['controller' => 'Organizations', 'action' => 'index'],
    'Fields' => ['controller' => 'Fields', 'action' => 'index'],
    'Junles' => ['controller' => 'Junles', 'action' => 'index'],
    'Works' => ['controller' => 'Works', 'action' => 'index'],
];
?>
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
<ul class="nav mt-2">
    <li class="nav-item">
            <?= $this->Html->link('Debug',['controller'=>'debug'], ['class' => 'nav-link']) ?>
    </li>
</ul>