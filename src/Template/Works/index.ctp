<?php

use Cake\Utility\Hash;
use Cake\Collection\Collection;
use App\Defines\Defines;

$loginUserGroup = $this->getLoginUser('group_id');
?>
<div class="card mt-2 border-primary">
    <div class="card-body py-2">
        <?= $this->Form->create(null, ['valueSources' => 'query']); ?>
        <div class="form-group row mb-0">
            <label class="col-2 col-form-label">Keyword</label>
            <div class="col-4">
                <?= $this->Form->text('keyword', ['class' => 'form-control']) ?>
            </div>
            <div class="col-6 text-right">
                <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i></button>
                <button class="btn btn-outline-primary ml-auto" type="button" data-toggle="collapse" data-target="#searchExtends" area-expanded="true" aria-controls="searchExtends"><i class="fa fa-caret-down"></i></button>
            </div>
        </div>
        <div class="collapse" id="searchExtends">
            <div class="form-group row mt-3">
                <label class="col-2 col-form-label">所属組織</label>
                <div class="col-4">
                    <?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control']) ?>
                </div>
                <label class="col-2 col-form-label">ジャンル</label>
                <div class="col-4">
                    <?= $this->Form->select('junle_id', $junles, ['class' => 'form-control']) ?>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-2 col-form-label">採点状況</label>
                <div class="col-4">
                    <?= $this->Form->select('mark-state', Defines::MARK_STATES, ['class' => 'form-control']) ?>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<table class="table table-bordered mt-2">
    <thead>
        <tr class="">
            <th class="">ID</th>
            <th class="">Name</th>
            <th class="">User</th>
            <th class="w-30">Junle</th>
            <th class="w-30">Skill</th>
            <th class="">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($works as $work): ?>
            <tr>
                <th class="text-nowrap text-right"><?= h($work->id) ?></th>
                <td class="text-nowrap"><?= h($work->name) ?></td>
                <td class="text-nowrap"><?= h($work->user->name) ?></td>
                <td>
                    <div class="text-truncate" style="width:20rem">
                        <?= h(implode(',', Hash::extract($work->junles, '{n}.name'))) ?>
                    </div>
                </td>
                <td>
                    <div class="text-truncate" style="width:20rem">
                        <?= $this->Element('skills', ['skills' => $work->getSkillsBest(3)]); ?>
                    </div>
                </td>
                <td class="text-nowrap py-0 align-middle">
                    <?php if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN, Defines::GROUP_MARKER])): ?>
                        <?= $this->Html->link('採点', ['controller' => 'works', 'action' => 'mark', $work->id], ['class' => 'btn btn-outline-primary btn-sm']) ?> 
                    <?php endif; ?>
                    <?php if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ENGINEER])): ?>
                        <?= $this->Html->link('編集', ['controller' => 'works', 'action' => 'edit', $work->id], ['class' => 'btn btn-outline-primary btn-sm']) ?> 
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="paginator">
    <ul class="pagination justify-content-center">
        <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>',['escape'=>false]) ?>
        <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>',['escape'=>false]) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>',['escape'=>false]) ?>
        <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>',['escape'=>false]) ?>
    </ul>
    <p class="text-right">
        <?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?>
    </p>
</div>

