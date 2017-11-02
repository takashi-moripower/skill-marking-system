<?php

use App\Defines\Defines;

$loginUser = $this->request->session()->read('Auth.User');

$i = 0;
$searchFormOpen = ( $this->request->getData('skill.1.id') != 0 || $this->request->getData('skill.2.id') != 0);
?>

<div class="text-right mb-2">
    <a href="<?= $this->Url->build(['controller' => 'engineers', 'action' => 'add']) ?>" class="btn btn-outline-primary">技術者追加</a>
</div>
<div class="card mt-2 border-primary">
    <div class="card-body py-2 px-3">
        <?= $this->Form->create(null, ['valueSources' => 'data' , 'url'=>['controller'=>'engineers', 'action'=>'index']]); ?>
        <div class="container-fluid px-0">
            <div class="form-group row mt-0 mb-1">
                <div class="col-9">
                    <?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control']) ?>
                </div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> Search</button>
                    <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'engineers', 'action' => 'index', 'clear' => 1]) ?>">Clear</a>
                    <button class="btn btn-outline-primary ml-auto" type="button" data-toggle="collapse" data-target="#searchExtends" area-expanded="true" aria-controls="searchExtends"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
            <div id="searchExtends" class="collapse <?= $searchFormOpen ? 'show' : '' ?>" >
                <div class="form-group row my-0">
                    <div class="col-9">
                        <?= $this->Element('engineers/skillform', ['i' => 0]) ?>
                        <?= $this->Element('engineers/skillform', ['i' => 1]) ?>
                        <?= $this->Element('engineers/skillform', ['i' => 2]) ?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<table class="table table-bordered mt-2">
    <thead>
        <tr class="">
            <th class="" rowspan="2">ID</th>
            <th class="w-15" rowspan="2">名称</th>
            <th class="bg-light">自己評価</th>
            <th class="" rowspan="2">操作</th>
        </tr>
        <tr>
            <th class="">他者評価</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <th rowspan="2"><?= h($user->id) ?></th>
                <td rowspan="2"><?= h($user->name) ?></th>
                <td class="p-0 align-middle bg-light"><?= $this->Element('skills', ['skills' => $user->self_skills, 'cardClass' => '']); ?></th>
                <td rowspan="2" class="py-0 align-middle">
                    <?= $this->Html->link('閲覧', ['controller' => 'engineers', 'action' => 'view', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?php if (in_array($loginUser->group_id, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN])): ?>
                        <?= $this->Html->link('編集', ['controller' => 'engineers', 'action' => 'edit', $user->id], ['class' => 'btn btn-sm btn-outline-primary py-0']); ?>
                    <?php endif; ?>
                    <?= $this->Html->link('評価', ['controller' => 'engineers', 'action' => 'view', $user->id], ['class' => 'btn btn-sm btn-secondary py-0 disabled']); ?>
                </td>
            </tr>
            <tr>
                <td class="p-0 align-middle"><?= $this->Element('skills', ['skills' => $user->max_skills]); ?></th>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->Element('paginator'); ?>

