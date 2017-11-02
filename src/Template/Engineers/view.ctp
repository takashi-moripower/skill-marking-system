<?php

use App\Defines\Defines;
?>
<div class="card">
    <div class="card-header">技術者情報　閲覧</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td class="border-top-0"><?= h($user->name); ?></td>
                </tr>
                <tr>
                    <th>所属</th>
                    <td>
                        <?php foreach ($user->organizations as $org): ?>
                            <?= $org->name ?>
                            <?= ($org !== end($user->organizations)) ? ',' : '' ?>
                        <?php endforeach ?>
                    </td>
                </tr>
                <tr>
                    <th>所属</th>
                    <td>
                        <?php foreach ($user->organizations as $org): ?>
                            <?= $org->name ?>
                            <?= ($org !== end($user->organizations)) ? ',' : '' ?>
                        <?php endforeach ?>
                    </td>
                </tr>
                <tr>
                    <th>自己アピール</th>
                    <td>
                        <?= $user->note ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="bg-light py-1">作品</th>
                </tr>
                <?php foreach ($user->works as $work): ?>
                    <tr>
                        <th><?= $this->Html->link(h($work->name), ['controller' => 'works', 'action' => 'view', $work->id]) ?></th>
                        <td><?= $this->Element('skills', ['skills' => $work->skills]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="text-right mt-1">
    <?php
    if ($this->getLoginUser('group_id') != Defines::GROUP_ENGINEER) {
        echo $this->Html->Link('一覧', ['controller' => 'engineers', 'action' => 'index'], ['class' => 'btn btn-outline-primary ml-1']);
    }
    echo $this->Html->Link('編集', ['controller' => 'engineers', 'action' => 'edit', $user->id], ['class' => 'btn btn-outline-primary ml-1']);
    ?>
</div>
