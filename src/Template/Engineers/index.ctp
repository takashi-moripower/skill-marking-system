<?php

use App\Defines\Defines;

$loginUser = $this->request->session()->read('Auth.User');
?>
<div class="text-right mb-2">
    <a href="<?= $this->Url->build(['controller' => 'engineers', 'action' => 'add']) ?>" class="btn btn-outline-primary">技術者追加</a>
</div>
<div class="card mt-2 border-primary">
    <div class="card-body py-2">
        <?= $this->Form->create(null, ['valueSources' => 'query']); ?>
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <?php
            $skill_id = $this->request->query("skill_id.{$i}");
            $skill_level = $this->request->query("skill_level.{$i}");
            if ($skill_level == null) {
                $skill_level = array();
            }
            ?>
            <div class="form-group row mt-3">
                <label class="col-2 col-form-label">スキル</label>
                <div class="col-3 p-0">
                    <?= $this->Form->select("skill_id[{$i}]", $skills, ['class' => 'form-control', 'value' => $skill_id]) ?>
                </div>
                <div class="col-2 pt-0 pb-0 pl-3">
                    <?php foreach ($levels as $level): ?>
                        <label class="d-inline-block mt-1">
                            <?= $level ?>
                            <?= $this->Form->checkbox("skill_level[{$i}][]", ['value' => $level, 'hiddenField' => false, 'checked' => in_array($level, $skill_level)]); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?php if ($i == 1): ?>
                    <div class="col-5 text-right">
                        <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>


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
                <td class="p-0 align-middle bg-light"><?= $this->Element('skills', ['skills' => $user->self_skills,'cardClass'=>'']); ?></th>
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

