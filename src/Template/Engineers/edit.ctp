<?php

use App\Defines\Defines;
use Cake\Utility\Hash;

$loginUserGroup = $this->getLoginUser('group_id');
?>
<?= $this->Form->create($user) ?>

<div class="card">
    <div class="card-header">    
        技術者情報　<?= ($this->request->action == 'edit') ? '編集' : '新規作成'; ?>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td class="border-top-0"><?= $this->Form->control('name', ['label' => false]); ?></td>
                </tr>
                <tr>
                    <th>email</th>
                    <td><?= $this->Form->control('email', ['label' => false]); ?></td>
                </tr>
                <tr>
                    <th>password</th>
                    <td><?= $this->Form->control('password', ['label' => false, 'value' => '']); ?></td>
                </tr>
                <tr>
                    <th>組織</th>
                    <td>
                        <?php if ($loginUserGroup != Defines::GROUP_ENGINEER): ?>
                            <?= $this->Form->control('organizations._ids', ['options' => $organizations, 'empty' => false, 'label' => false, 'multiple' => true,]); ?>
                        <?php else: ?>
                            <?php foreach ($user->organizations as $org): ?>
                                <div><?= $org->path_name ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>自己アピール</th>
                    <td><?= $this->Form->control('note', ['label' => false]) ?></td>
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
<div class="text-right mt-1">
    <?php
    if ($loginUserGroup != Defines::GROUP_ENGINEER) {
        echo $this->Html->Link('一覧', ['controller' => 'engineers', 'action' => 'index'], ['class' => 'btn btn-outline-primary ml-1']);
    }
    echo $this->Html->Link('閲覧', ['controller' => 'engineers', 'action' => 'view', $user->id], ['class' => 'btn btn-outline-primary ml-1']);
    ?>
</div>

