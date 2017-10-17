<?php

use App\Defines\Defines;

$loginUserGroup = $this->getLoginUser('group_id');
?>
<table class="table table-bordered">
    <tbody>
        <tr>
            <th class="w-25">題名</th>
            <td><?= h($work->name) ?></td>
        </tr>
        <tr>
            <th>ジャンル</th>
            <td>
                <?php foreach ($work->junles as $junle): ?>
                    <?= h($junle->name) ?>
                    <?= ($junle !== end($work->junles)) ? ',' : '' ?>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <th>投稿者</th>
            <td><?= $this->Html->link(h($work->user->name), ['controller' => 'engineers', 'action' => 'view', $work->user->id]) ?></td>
        </tr>
        <tr>
            <th>解説</th>
            <td><?= nl2br(h($work->note)) ?></td>
        </tr>
        <tr>
            <th>添付ファイル</th>
            <td>
                <?php foreach ($work->files as $file): ?>
                    <?= $this->Element('files/thumbnail', ['file' => $file]) ?>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <th>スキル</th>
            <td>
                <?php
                echo $this->Element('skills', ['skills' => $work->skills]);
                ?>
            </td>
        </tr>

    </tbody>
</table>

<div class="text-right">
    <?php
    if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN, Defines::GROUP_ENGINEER])) {
        echo $this->Html->Link('編集', ['controller' => 'works', 'action' => 'edit', $work->id], ['class' => 'btn btn-outline-primary']);
    }
    if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN, Defines::GROUP_MARKER])) {
        echo $this->Html->Link('採点', ['controller' => 'works', 'action' => 'mark', $work->id], ['class' => 'btn btn-outline-primary']);
    }
    ?>
</div>