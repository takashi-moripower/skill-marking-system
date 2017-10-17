<?php 
use App\Defines\Defines;
$group = $this->getLoginUser('group_id');
$empty = ($group == Defines::GROUP_ADMIN);
?>
<h2 class="mb-2">
    スキル分野　<?= ($this->request->action == 'edit') ? '編集' : '新規作成'; ?>
</h2>
<?= $this->Form->create($field) ?>
<table class="table table-bordered">
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
            <td><?= $this->Form->control('organization_id', ['options' => $organizations, 'empty' => true, 'label' => false]); ?></td>
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

<?= $this->Form->end ?>