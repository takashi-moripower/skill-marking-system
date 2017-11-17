<?php

use App\Defines\Defines;
use Cake\Utility\Hash;

$loginUserGroup = $this->getLoginUser('group_id');
?>

<?= $this->Form->create($user) ?>
<div class="card">
    <div class="card-header">    
        <?= Hash::get($user, 'group.name', 'ユーザー') ?>情報　<?= (!$this->request->action == 'add') ? '編集' : '新規作成'; ?>
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
                        <?php
                        if ( in_array( $loginUserGroup  , [Defines::GROUP_ADMIN , Defines::GROUP_ORGANIZATION_ADMIN ])) {
                            echo $this->Form->control('organizations._ids', ['options' => $organizations, 'empty' => false, 'label' => false, 'multiple' => true,]);

                            $org_set = Hash::extract($user, 'organizations.{n}.id');
                            $org_editable = array_keys($organizations->toArray());
                            $org_fix = array_diff($org_set, $org_editable);
                            foreach ($org_fix as $org_id) {
                                echo $this->Form->hidden('organizations._ids[]', ['value' => $org_id]);
                            }
                        } else {
                            foreach ($user->organizations as $org) {
                                echo "<div>{$org->path_name}</div>";
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>自己アピール</th>
                    <td><?= $this->Form->control('note', ['label' => false, 'class' => 'w-100']) ?></td>
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
