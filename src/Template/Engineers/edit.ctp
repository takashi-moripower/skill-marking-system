<h2 class="mb-2">
    技術者　<?= ($this->request->action == 'edit') ? '編集' : '新規作成'; ?>
</h2>
<?= $this->Form->create($user) ?>
<table class="table table-bordered">
    <tbody>
        <tr>
            <th>名称</th>
            <td><?= $this->Form->control('name', ['label' => false]); ?></td>
        </tr>
        <tr>
            <th>email</th>
            <td><?= $this->Form->control('email', ['label' => false]); ?></td>
        </tr>
        <tr>
            <th>password</th>
            <td><?= $this->Form->control('password', ['label' => false , 'value'=>'']); ?></td>
        </tr>
        <tr>
            <th>組織</th>
            <td><?= $this->Form->control('organizations._ids', ['options' => $organizations, 'empty' => false, 'label' => false, 'multiple' => true,]);?></td>
        </tr>
        <tr>
            <td colspan="2">
                <div  class="text-right" >
                    <?= $this->Form->button('保存', ['class' => 'btn btn-primary']) ?>
                    <?= $this->Html->link('一覧に戻る', ['controller' => 'engineers', 'action' => 'index'], ['class' => 'btn btn-secondary']) ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<?= $this->Form->end ?>