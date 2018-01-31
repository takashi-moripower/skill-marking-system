<?php

use App\Defines\Defines;
use App\Utility\MyUtil;

$skills = $statistics->getSkills();
?>

<div class="card my-2 border-primary">
    <div class="card-body p-2">
        <?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'statistics', 'action' => 'skills']]); ?>
        <table class="table table-sm ">
            <tbody>
                <tr>
                    <th>所属</th>
                    <td><?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control', 'empty' => true]) ?></td>
                    <td class="text-right">
                        <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                        <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'statistics', 'action' => 'skills', 'clear' => 1]) ?>">クリア</a>
                    </td>
                </tr>
                <tr>
                    <th>年齢</th>
                    <td>
                        最低：<?= $this->Form->select('min_age', MyUtil::getAges()); ?>　～
                        最高：<?= $this->Form->select('max_age', MyUtil::getAges()); ?>
                    </td>
                </tr>
                <tr>
                    <th>性別</th>
                    <td>
                        <?= $this->Form->select('sex', Defines::CONDITION_SEX_OPTIONS) ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?= $this->Form->end() ?>
    </div>
</div>


<table class="table table-bordered table-sm">
    <tbody>
        <tr>
            <th>スキル名</th>
            <?php for ($l = 1; $l <= Defines::SKILL_LEVEL_MAX; $l++): ?>
                <th><?= $l ?></th>
            <?php endfor; ?>
            <th>計</th>
            <th>平均</th>
        </tr>
        <?php foreach ($skills as $skill): ?>
            <tr>
                <th><?= $skill->label ?></th>
                <?php for ($l = 1; $l <= Defines::SKILL_LEVEL_MAX; $l++): ?>
                    <td class="text-right">
                        <?php
                        $c = "count_{$l}";
                        echo $skill->{$c};
                        ?>
                    </td>
                <?php endfor; ?>
                <td class="text-right">
                    <?= $skill->count ?>
                </td>
                <td class="text-right">
                    <?= number_format($skill->average, 2) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->element('statistics/chart', ['skills' => $skills]) ?>
