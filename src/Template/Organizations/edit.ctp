<?php

use App\Defines\Defines;

$group = $this->getLoginUser('group_id');
$empty = ($group == Defines::GROUP_ADMIN);
?>
<?= $this->Form->create($organization) ?>

<div class="card">
    <div class="card-header border-bottom-0">
        組織　<?= ($this->request->action == 'edit') ? '編集' : '新規作成'; ?>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <tbody>
                <tr>
                    <th class="">名称</th>
                    <td class=""><?= $this->Form->text('name') ?></td>
                </tr>
                <?php if (!empty($parentOrganizations->toArray())): ?>
                    <tr>
                        <th>親組織</th>
                        <td><?= $this->Form->control('parent_id', ['options' => $parentOrganizations, 'empty' => $empty, 'label' => false]); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th>組織情報</th>
                    <td><?= $this->Form->textArea('note', ['class' => 'w-100']) ?></td>
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