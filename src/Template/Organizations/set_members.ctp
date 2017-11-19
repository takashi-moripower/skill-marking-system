<?php
use Cake\Utility\Hash;

$members = Hash::extract($organization, 'users.{n}.id');
?>
<?= $this->Form->create($organization) ?>
<?= $this->Form->hidden('id'); ?>

<div class="card">
    <div class="card-header">
        <h2><?= $organization->name ?>　メンバー設定</h2>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered">
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <th><?= $this->Form->checkbox('users.ids[]', ['value' => $user->id, 'label' => false, 'checked' => in_array($user->id, $members)]) ?></th>
                        <td><?= h($user->name) ?></td>
                        <td>
                            <?php foreach( $user->organizations as $org ): ?>
                            <div><?= h( $org->path_name )?></div>
                            <?php endforeach ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (false): ?>
            <?= $this->Form->select('users._ids', $list, ['multiple' => 'checkbox']); ?>
        <?php endif; ?>
        <div  class="text-right" >
            <?= $this->Form->button('保存', ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
        </div>
    </div>

</div>
<?= $this->Form->end() ?>

<?= $this->Element('paginator'); ?>