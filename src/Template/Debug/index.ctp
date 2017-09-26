<?php

use Cake\Utility\Hash;

$dummies = [
    '組織' => 'Organizations',
    'ユーザー' => 'Users',
    'ジャンル' => 'Junles',
    'スキル分野' => 'Fields',
    'スキル' => 'skills',
    '作品' => 'works',
    '採点' => 'marks',
];
?>
<table class="table">
    <tbody>
        <tr>
            <td>
                <?= $this->Html->link("TEST", ['controller' => 'debug', 'action' => "test"]); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= $this->Html->link("Login AS", ['controller' => 'debug', 'action' => "loginAs"]); ?>
            </td>
        </tr>
        <?php foreach ($dummies as $label => $table): ?>
            <tr>
                <td>
                    <?= $this->Html->link("Dummy{$label}生成", ['controller' => 'debug', 'action' => "createDummy{$table}"]); ?>
                </td>
                <td>
                    <?= $this->Html->link("{$label}全消去", ['controller' => 'debug', 'action' => "truncate{$table}"]); ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>