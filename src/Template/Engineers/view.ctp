<?php

use App\Defines\Defines;
use Cake\Utility\Hash;
use App\Utility\MyUtil;

$loginUserId = $this->getLoginUser('id');
$loginUserGroup = $this->getLoginUser('group');
?>
<div class="card">
    <div class="card-header">技術者情報</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td colspan="3" class="border-top-0"><?= h($user->name); ?></td>
                </tr>
                <tr>
                    <th>所属</th>
                    <td colspan="3">
                        <?php foreach ($user->organizations as $org): ?>
                            <?= $org->name ?>
                            <?= ($org !== end($user->organizations)) ? ',' : '' ?>
                        <?php endforeach ?>
                    </td>
                </tr>
                <tr>
                    <th>ユーザ紹介</th>
                    <td colspan="3">
                        <?= MyUtil::strip_tags($user->note) ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="4" class="bg-light py-1">作品</th>
                </tr>
                <?php foreach ($user->works as $work): ?>
                    <tr>
                        <th><?= h($work->name) ?></th>
                        <td>
                            <?= $this->Element('skills/colored_skills', ['skills' => $work->skills, 'user_id' => $work->user_id, 'flags' => Defines::SKILL_DISPLAY_FLAG_FOR_ENGINEERS]); ?>
                        </td>
                        <td>
                            <?= Hash::check($work->skills, "{n}._joinData[user_id={$loginUserId}]") ? '済' : '未'; ?>
                        </td>
                        <td><?= $this->Html->link('採点', ['controller' => 'works', 'action' => 'mark', $work->id], ['class' => 'btn btn-outline-primary btn-sm']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="text-right mt-1">
    <?php
    if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN])) {
        echo $this->Html->Link('編集', ['controller' => 'engineers', 'action' => 'edit', $user->id], ['class' => 'btn btn-outline-primary ml-1']);
    }
    ?>
</div>
