
<?= $this->Form->create($skill) ?>

<div class="card">
    <div class="card-header">
        スキル　<?= ($this->request->action == 'edit') ? '編集' : '新規作成'; ?>
    </div>
    <div class="card-body p-0">
        <table class="table m-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td class="w-80 border-top-0"><?= $this->Form->control('name', ['label' => false]); ?></td>
                </tr>
                <tr>
                    <th>フィールド</th>
                    <td><?= $this->Form->control('field_id', ['options' => $fields, 'empty' => false, 'label' => false]); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div  class="text-right mt-2" >
    <?= $this->Form->button('保存', ['class' => 'btn btn-primary ml-1']) ?>
    <?= $this->Html->link('一覧に戻る', ['controller' => 'skills', 'action' => 'index'], ['class' => 'btn btn-secondary  ml-1']) ?>
</div>
<?= $this->Form->end ?>
