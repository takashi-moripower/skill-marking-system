<?php

use App\Defines\Defines;
?>
<div class="text-right">
    <?= $this->Html->link('新規ユーザ',['action'=>'add']) ?>
</div>
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>権限</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <th><?= $user->id ?></th>
                <th><?= $user->name ?></th>
                <td><?= $user->group->name ?></td>
                <td class="py-0 align-middle">
                    <?= $this->Html->link('編集', ['controller' => 'users', 'action' => 'edit', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>