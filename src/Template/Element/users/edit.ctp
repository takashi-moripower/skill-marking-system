<?php

use App\Defines\Defines;
use App\Utility\MyUtil;
use Cake\Utility\Hash;

$loginUserGroup = $this->getLoginUser('group_id');

$nameEditable = true;
if ($loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN && $user->group_id == Defines::GROUP_MARKER && !$user->isNew()) {
    $nameEditable = false;
}

$groupEditable = false;
if ($loginUserGroup == Defines::GROUP_ADMIN || ($loginUserGroup == Defines::GROUP_ORGANIZATION_ADMIN && $user->isNew() )) {
    $groupEditable = true;
}

$orgsEditable = false;
if( in_array( $loginUserGroup , [Defines::GROUP_ADMIN , Defines::GROUP_ORGANIZATION_ADMIN ]) && $user->group_id != Defines::GROUP_ADMIN ){
    $orgsEditable = true;
}

$this->Form->templates(Defines::FORM_TEMPLATE_RADIO + Defines::FORM_TEMPLATE_DATE);
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
                    <td class="border-top-0">
                        <?php
                        if ($nameEditable) {
                            echo $this->Form->control('name', ['label' => false, 'class' => 'w-100', 'default' => '']);
                        } else {
                            echo $user->name;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>email</th>
                    <td>
                        <?php
                        if ($nameEditable) {
                            echo $this->Form->control('email', ['label' => false, 'class' => 'w-100', 'default' => '']);
                        } else {
                            echo $user->email;
                        }
                        ?>
                    </td>
                </tr>
                <?php if ($nameEditable): ?>
                    <tr>
                        <th>password</th>
                        <td><?= $this->Form->control('password', ['label' => false, 'value' => '', 'class' => 'w-100']); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tbody group="groups" class="<?= ($this->request->params['controller'] == 'Users') ? '' : 'd-none' ?>">
                <tr>
                    <th>権限</th>
                    <td>
                        <?php
                        if ($groupEditable) {
                            echo $this->Form->select('group_id', $groups, ['label' => false]);
                        } else {
                            echo $user->group->name;
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
            <tbody group="organizations">
                <tr>
                    <th>
                        組織
                        <?php
                        if ($orgsEditable) {
                            echo $this->Element('popup_hint', ['message' => '一つもチェックを入れなかった場合　そのユーザーは管轄外となります<br/>管轄外のユーザーはユーザー一覧に表示されず　編集操作もできません']);
                        }
                        ?>
                    </th>
                    <td>
                        <?php
                        if ($orgsEditable) {
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
            </tbody>
            <tbody>
                <tr>
                    <th>ユーザ紹介</th>
                    <td>
                        <?php
                        if ($nameEditable) {
                            echo $this->Form->control('note', ['label' => false, 'class' => 'w-100', 'id' => 'editor']);
                        } else {
                            echo MyUtil::strip_tags($user->note);
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
            <tbody group="engineer" class="<?= ($user->group_id != Defines::GROUP_ENGINEER ? '' : 'd-none') ?>">
                <tr>
                    <th>性別</th>
                    <td>
                        <?= $this->Form->hidden('engineer.user_id', ['value' => $user->id]) ?>
                        <?= $this->Form->radio('engineer.sex', Defines::USERS_SEX, ['default' => Defines::SEX_MALE]) ?>
                    </td>
                </tr>
                <tr>
                    <th>誕生日</th>
                    <td><?= $this->Form->control('engineer.birthday', ['type' => 'date', 'label' => false, 'monthNames' => false, 'minYear' => 1950]) ?></td>
                </tr>
            </tbody>
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

<?php $this->append('script', $this->Html->script('/js/ckeditor/ckeditor.js')) ?>
<?php $this->append('script'); ?>
<script>
    var GROUP_ENGINEER = <?= Defines::GROUP_ENGINEER ?>;
    $(function () {
        $('select[name="group_id"]').on('change', onChangeGroup);

        onChangeGroup();

        function onChangeGroup(event) {
            var group_id = $('select[name="group_id"]').val();
            if (group_id == GROUP_ENGINEER) {
                $('tbody[group="engineer"]').removeClass('d-none');
                $('tbody[group="engineer"]').find('select,input').removeAttr('disabled');
            } else {
                $('tbody[group="engineer"]').addClass('d-none');
                $('tbody[group="engineer"]').find('select,input').attr('disabled', 'disabled');
            }
        }
    });
</script>
<?php $this->end() ?>
