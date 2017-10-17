<h2 class="mb-2">
    スキル　<?= ($this->request->action == 'edit') ? '編集' : '新規作成'; ?>
</h2>
<?= $this->Form->create($skill) ?>
<table class="table table-bordered">
    <tbody>
        <tr>
            <th>名称</th>
            <td><?= $this->Form->control('name',['label'=>false]); ?></td>
        </tr>
        <tr>
            <th>フィールド</th>
            <td><?= $this->Form->control('field_id', ['options' => $fields, 'empty' => false, 'label' => false]); ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <div  class="text-right" >
                    <?= $this->Form->button('保存', ['class' => 'btn btn-primary']) ?>
                    <?= $this->Html->link('一覧に戻る', ['controller' => 'skills', 'action' => 'index'], ['class' => 'btn btn-secondary']) ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<?= $this->Form->end ?>