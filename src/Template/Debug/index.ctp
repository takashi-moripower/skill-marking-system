<?php

use Cake\Utility\Hash;

$navs = [
    'Dummy組織生成' => ['controller'=>'debug','action'=>'createDummyOrganizations'],
    '組織全消去' => ['ontroller'=>'debug' , 'action' =>'truncateOrganizations'],
    'Dummyユーザー生成' => ['controller'=>'debug','action'=>'createDummyUsers'],
    'ユーザー全消去' => ['ontroller'=>'debug' , 'action' =>'truncateUsers'],
    'Dummyジャンル生成' => ['controller'=>'debug','action'=>'createDummyJunles'],
    'ジャンル全消去' => ['ontroller'=>'debug' , 'action' =>'truncateJunles'],    
];
?>
<ul class="nav flex-column">
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

