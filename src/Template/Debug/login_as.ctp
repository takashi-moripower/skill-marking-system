<?php
use Cake\Utility\Hash;
?>
<table class="table table-sm">
    <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
            <th>Organization</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td class="text-nowrap">
                    <?= $this->Html->link(h($user->name), ['controller' => 'debug', 'action' => 'loginAs', $user->id]) ?>
                </td>
                <td>
                    <?= h($user->group->name) ?>
                </td>
                <td>
                    <?= h(implode(',',Hash::extract($user,'organizations.{n}.name')))?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

