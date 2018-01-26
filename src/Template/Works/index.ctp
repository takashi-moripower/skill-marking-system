<?php

use Cake\Utility\Hash;
use Cake\Collection\Collection;
use App\Defines\Defines;

$loginUserName = $this->getLoginUser('name');
$loginUserGroup = $this->getLoginUser('group_id');

$searchFormOpen = !empty($this->request->data);

$displayName = ($loginUserGroup != Defines::GROUP_ENGINEER);
?>
<div class="card mt-2 border-primary">
    <div class="card-body py-2">
        <?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'works', 'action' => 'index']]); ?>
        <div class="form-group row mb-0">
            <label class="col-2 col-form-label">キーワード</label>
            <div class="col-4">
                <?= $this->Form->text('keyword', ['class' => 'form-control', 'placeHolder' => 'keyword']) ?>
            </div>
            <div class="col-6 text-right">
                <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索 </button>
                <a href="<?= $this->Url->build(['controller' => 'works', 'action' => 'index', 'clear' => true]) ?>" class="btn btn-outline-primary mr-2">クリア</a>
                <button class="btn btn-outline-primary ml-auto" type="button" data-toggle="collapse" data-target="#searchExtends" area-expanded="true" aria-controls="searchExtends"><i class="fa fa-caret-down"></i></button>
            </div>
        </div>
        <div id="searchExtends" class="collapse <?= $searchFormOpen ? 'show' : '' ?>" >
            <div class="form-group row mb-0">
                <label class="col-2 col-form-label mt-1">ジャンル</label>
                <div class="col-4 mt-1">
                    <?= $this->Form->select('junle_id', $junles, ['class' => 'form-control']) ?>
                </div>
                <label class="col-2 col-form-label mt-1">採点状況</label>
                <div class="col-4 mt-1">
                    <?= $this->Form->select('mark-state', Defines::MARK_STATES, ['class' => 'form-control']) ?>
                </div>
                <?php if ($loginUserGroup != Defines::GROUP_ENGINEER): ?>
                    <label class="col-2 col-form-label mt-1">所属組織</label>
                    <div class="col-4 mt-1">
                        <?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control', 'empty' => 'すべて']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<table class="table table-bordered mt-2">
    <thead>
        <tr class="">

            <th class="">作品名</th>
            <?php if ($displayName): ?> 
                <th class="">作者</th>
            <?php endif; ?>
            <th class="w-20">ジャンル</th>
            <th>採点</th>
            <th class="w-50">
                スキル評価
                <div class="float-right">
                    <?= $this->Element('skills/samples'); ?>
                </div>
            </th>
            <th class="">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($works as $work): ?>
            <tr>

                <td class="text-nowrap"><?= h($work->name) ?></td>
                <?php if ($displayName): ?> 
                    <td class="text-nowrap"><?= h($work->user->name) ?></td>
                <?php endif; ?>
                <td>
                    <div class="">
                        <?= h(implode(',', Hash::extract($work->junles, '{n}.name'))) ?>
                    </div>
                </td>
                <td>
                    <?= $work->mark ? '済' : '未' ?>
                </td>
                <td>
                    <div class="">
                        <?= $this->Element('skills/colored_skills', ['skills' => $work->skills, 'user_id' => $work->user_id, 'flags' => Defines::SKILL_DISPLAY_FLAG_FOR_WORKS]); ?>
                    </div>
                </td>
                <td class="text-nowrap py-0 align-middle">
                    <?= $this->Html->link('採点', ['controller' => 'works', 'action' => 'mark', $work->id], ['class' => 'btn btn-outline-primary btn-sm']) ?> 
                    <?php if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN, Defines::GROUP_ENGINEER])): ?>
                        <?= $this->Html->link('編集', ['controller' => 'works', 'action' => 'edit', $work->id], ['class' => 'btn btn-outline-primary btn-sm']) ?> 
                        <?= $this->Html->link('削除', ['controller' => 'works', 'action' => 'delete', $work->id], ['class' => 'btn btn-outline-danger btn-sm', 'role' => 'delete']) ?> 
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->Element('paginator') ?>

<?php $this->append('script'); ?>
<script>
    $(function () {
        $(document).on('click', 'a.btn[role="delete"]', function () {
            return confirm('realy delete?');
        });
    });
</script>
<?php $this->end(); ?>