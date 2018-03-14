<?php

use App\Defines\Defines;
use App\Utility\MyUtil;
use Cake\Utility\Hash;

$loginUserGroup = $this->getLoginUser('group_id');

$this->Form->templates(Defines::FORM_TEMPLATE_RADIO + Defines::FORM_TEMPLATE_DATE);
?>

<?= $this->Form->create($user) ?>
<?= $this->Form->hidden('token', ['value' => $token]); ?>
<?= $this->Form->hidden('valid_email', ['value' => 0]); ?>
<div class="card mt-2">
    <div class="card-header">    
        新規登録
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td class="border-top-0">
                        <?php
                        echo $this->Form->control('name', ['label' => false, 'class' => 'w-100', 'default' => '']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>email</th>
                    <td>
                        <?php
                        echo $this->Form->control('email', ['label' => false, 'class' => 'w-100', 'default' => '']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>所属</th>
                    <td>
                        <?php
                        echo $this->Form->select('organization_id', $organizations);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        卒業年
                        <?= $this->Element('popup_hint',['message'=>'年度ではなく、年です<br/>2018年3月卒業の場合は<br/>2018を入力してください'])?>
                    </th>
                    <td>
                        <?php
                        $year_now = Date('Y');
                        echo $this->Form->input('graduation_year',['value'=>$year_now,'label'=>false,'class'=>'text-right']);
                        ?>
                    </td>
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
