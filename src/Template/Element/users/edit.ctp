<?php

use App\Defines\Defines;
use Cake\Utility\Hash;

$loginUserGroup = $this->getLoginUser('group_id');

$this->Form->templates([
    'dateWidget' => '{{year}} 年 {{month}} 月 {{day}} 日 ',
]);
?>

<?= $this->Form->create($user) ?>
<div class="card">
    <div class="card-header">    
        <?= Hash::get($user, 'group.name', 'ユーザー') ?>情報　<?= ($user->isNew() ? '新規作成' : '編集'); ?>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td class="border-top-0"><?= $this->Form->control('name', ['label' => false, 'class' => 'w-100', 'default' => '']); ?></td>
                </tr>
                <tr>
                    <th>email</th>
                    <td><?= $this->Form->control('email', ['label' => false, 'class' => 'w-100', 'default' => '']); ?></td>
                </tr>
                <tr>
                    <th>password</th>
                    <td><?= $this->Form->control('password', ['label' => false, 'value' => '', 'class' => 'w-100']); ?></td>
                </tr>
                <?php if ($this->request->params['controller'] == 'Users'): ?>
                    <tr>
                        <th>権限</th>
                        <td><?= $this->Form->select('group_id', $groups, ['label' => false]); ?></td>
                    </tr>
                <?php endif ?>
                <tr>
                    <th>
                        組織
                        <?= $this->Element('popup_hint',['message'=>'一つもチェックを入れなかった場合　そのユーザーは管轄外となります<br/>管轄外のユーザーはユーザー一覧に表示されず　編集操作もできません'])?>
                    </th>
                    <td>
                        <?php
                        if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN])) {
                            echo $this->Form->control('organizations._ids', ['options' => $organizations, 'empty' => false, 'label' => false, 'multiple' => 'checkbox',]);

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
                    <th>ユーザ紹介</th>
                    <td><?= $this->Form->control('note', ['label' => false, 'class' => 'w-100' ,'id'=>'editor']) ?></td>
                </tr>
            </tbody>
            <?php if ($user->group_id == Defines::GROUP_ENGINEER): ?>
                <tbody>
                    <tr>
                        <th>性別</th>
                        <td>
                            <?= $this->Form->hidden('engineer.user_id', ['value' => $user->id]) ?>
                            <?= $this->Form->select('engineer.sex', Defines::USERS_SEX, ['label' => false]) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>誕生日</th>
                        <td><?= $this->Form->control('engineer.birthday', ['type' => 'date', 'label' => false, 'monthNames' => false, 'minYear' => 1950]) ?></td>
                    </tr>
                </tbody>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <div  class="text-right" >
                            <?= $this->Form->button('保存', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?= $this->Form->end ?>
<script>
    // エディタへの設定を適用する
    CKEDITOR.replace('editor', {
        uiColor: '#EEEEEE',
        height: 400
    });
</script>

<?php $this->append('script' , $this->Html->script('/js/ckeditor/ckeditor.js')) ?>
