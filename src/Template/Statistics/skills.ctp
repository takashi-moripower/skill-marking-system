<?php

use App\Defines\Defines;
use App\Utility\MyUtil;

$skills = $statistics->getSkills();
?>

<div class="card my-2 border-primary">
    <div class="card-body py-2 px-3">
        <?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'statistics', 'action' => 'skills']]); ?>
        <div class="container-fluid px-0">
            <div class="form-group row mt-0 mb-2">
                <div class="col-9">
                    <div class="row">
                        <div class="col-2">
                            所属
                        </div>
                        <div class="col-10 p-0">
                            <?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control', 'empty' => true]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                    <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'statistics', 'action' => 'skills', 'clear' => 1]) ?>">クリア</a>
                </div>
            </div>
            <div class="form-group row my-0">
                <div class="col-9">
                    <div class='row mb-1'>
                        <div class="col-2">年齢</div>
                        <div class="col-6 p-0">
                            最低：<?= $this->Form->select('min_age', MyUtil::getAges()); ?>　～
                            最高：<?= $this->Form->select('max_age', MyUtil::getAges()); ?>
                        </div>
                    </div>
                    <div class='row mb-1'>
                        <div class="col-2">性別</div>
                        <div class="col-2 p-0"><?= $this->Form->select('sex', Defines::CONDITION_SEX_OPTIONS) ?></div>
                    </div>
                </div>
            </div>
        </div>
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
                    <td class="text-right"><?= $statistics->count($skill->id, $l) ?></td>
                <?php endfor; ?>
                <td class="text-right">
                    <?= $statistics->count($skill->id, null) ?>
                </td>
                <td class="text-right">
                    <?= number_format($statistics->average($skill->id), 2) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
