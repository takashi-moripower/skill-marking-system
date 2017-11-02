
<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$loginUser = $this->request->session()->read('Auth.User');
?>
<div class="text-right mb-2">
    <a href="<?= $this->Url->build(['controller'=>'skills','action'=>'add'])?>" class="btn btn-outline-primary">新規作成</a>
</div>

<div class="card mb-2">
    <div class="card-body py-2 px-3">
        <?= $this->Form->create(null, ['valueSources' => 'data' , 'url'=>['controller'=>'skills', 'action'=>'index']]); ?>
        <div class="container-fluid px-0">
            <div class="form-group row mt-0 mb-1">
                <div class="col-9">
                    <?= $this->Form->select('field_id', $fields, ['class' => 'form-control' , 'empty'=>true]) ?>
                </div>
                <div class="col-3 text-right">
                    <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> Search</button>
                    <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'skills', 'action' => 'index', 'clear' => 1]) ?>">Clear</a>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>管轄組織</th>
            <th>スキル分野</th>
            <th>名称</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($skills as $skill): ?>
            <tr>
                <td class="text-right"><?= $skill->id ?></td>
                <td>
                    <?= Hash::get($skill, 'org_name', '共通') ?>
                </td>
                <td>
                    <?= h($skill->field_path) ?>
                </td>
                <td>
                    <?= h($skill->name) ?>
                </td>
                <td class="pt-0 pb-0 align-middle">
                    <?php if ($loginUser->group_id == Defines::GROUP_ADMIN || ($loginUser->group_id == Defines::GROUP_ORGANIZATION_ADMIN && $skill->field->organization_id != null)): ?>
                        <?= $this->Html->link('編集',['controller' => 'skills', 'action' => 'edit', $skill->id],['class'=>'btn btn-sm btn-outline-primary py-0']); ?>
                        <?= $this->Html->link('削除',['controller' => 'skills', 'action' => 'delete', $skill->id],['class'=>'btn btn-sm btn-outline-danger py-0','role'=>'delete']); ?>
                    <?php else: ?>
                        <a class="btn btn-sm btn-outline-primary pt-0 pb-0 disabled">編集</a>
                        <a class="btn btn-sm btn-outline-danger pt-0 pb-0 disabled">削除</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?= $this->Element('paginator') ?>

<?php $this->append('script'); ?>
<script>
$(function(){
    $(document).on('click','a.btn[role="delete"]',function(){
        return confirm('realy delete?');
    });
});
</script>
<?php $this->end(); ?>