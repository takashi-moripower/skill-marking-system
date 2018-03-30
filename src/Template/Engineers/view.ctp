<?php

use App\Defines\Defines;
use Cake\Utility\Hash;
use App\Utility\MyUtil;
use Cake\ORM\TableRegistry;

$loginUserId = $this->getLoginUser('id');
$loginUserGroup = $this->getLoginUser('group_id');
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
                    <th>所属組織</th>
                    <td colspan="3">
                        <?= $this->element('organizations/list', ['organizations' => $user->organizations]) ?>
                    </td>
                </tr>
                <tr>
                    <th>ユーザ紹介</th>
                    <td colspan="3">
                        <?= MyUtil::strip_tags($user->note) ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-light py-1">
                        作品
                    </th>
                    <th class="bg-light text-right py-1">
                        <div class="float-left">スキル評価</div>
                        <div class="float-right">
                            <?= $this->Element('skills/samples') ?>
                        </div>
                    </th>
                    <th  colspan="2" class="bg-light">
                    </th>
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

<h3 class="my-3">データ解析<?= $this->Element('popup_hint', ['message' => '評価数及び偏差値に、作者自身による評価は含まれません']) ?></h3>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="#tab1" class="nav-link active" data-toggle="tab">スキル分布</a>
    </li>
    <li class="nav-item">
        <a href="#tab2" class="nav-link" data-toggle="tab">偏差値</a>
    </li>
</ul>
<div class="tab-content mb-5">
    <div id="tab1" class="tab-pane active">
        <?php
        $skills = TableRegistry::get('Skills')->getSkillsForChart()
                ->where(['Works.user_id' => $user->id])
                ->where(['SkillsWorks.user_id <>' => $user->id]);

        echo $this->Element('statistics/table', compact('skills'));
        echo $this->Element('statistics/chart', compact('skills'))
        ?>
    </div>
    <div id="tab2" class="tab-pane">
        <?= $this->Element('engineers/deviation', ['user' => $user]) ?>
    </div>
</div>