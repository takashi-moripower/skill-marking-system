<?php

use App\Defines\Defines;

$group = $this->getLoginUser('group_id');
$org_empty = ($group == Defines::GROUP_ADMIN);
?>
<?= $this->Form->create($field) ?>

<div class="card">
    <div class="card-header border-bottom-0">
        スキル分野　<?= ($this->request->action == 'edit') ? '編集' : '新規作成'; ?>

    </div>
    <div class="card-body p-0">
        <table class="table table-bordered m-0">
            <tbody>
                <tr>
                    <th>名称</th>
                    <td><?= $this->Form->text('name') ?></td>
                </tr>
                <tr>
                    <th>親スキル分野</th>
                    <td><?= $this->Form->control('parent_id', ['options' => $parentFields, 'empty' => true, 'label' => false]); ?></td>
                </tr>
                <tr>
                    <th>組織</th>
                    <td><?= $this->Form->control('organization_id', ['options' => $organizations, 'empty' => $org_empty, 'label' => false]); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div  class="text-right" >
                            <?= $this->Form->button('保存', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->Form->end ?>