<?php

use App\Defines\Defines;
use App\Utility\MyUtil;

$loginUserGroup = $this->getLoginUser('group_id');
?>
<div class="card">
    <div class="card-header">
        作品　閲覧
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">題名</th>
                    <td class="border-top-0"><?= h($work->name) ?></td>
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
                    <td><?= MyUtil::strip_tags($work->note) ?></td>
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
    </div>
</div>

<div class="text-right mt-1">
    <?php
        echo $this->Html->Link('一覧', ['controller' => 'works', 'action' => 'index'], ['class' => 'btn btn-outline-primary ml-1']);
    if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN, Defines::GROUP_ENGINEER])) {
        echo $this->Html->Link('編集', ['controller' => 'works', 'action' => 'edit', $work->id], ['class' => 'btn btn-outline-primary ml-1']);
    }
    echo $this->Html->Link('採点', ['controller' => 'works', 'action' => 'mark', $work->id], ['class' => 'btn btn-outline-primary ml-1']);
    ?>
</div>