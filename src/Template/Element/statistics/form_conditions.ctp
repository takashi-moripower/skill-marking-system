<?php

use App\Defines\Defines;
use App\Utility\MyUtil;

$this->Form->templates(
        Defines::FORM_TEMPLATE_INLINE_CHECKBOX
);
?>
<?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'statistics', 'action' => 'conditions']]); ?>
<table class="table table-sm table-bordered ">
    <tbody>
        <tr>
            <th rowspan="1" colspan="2">
                募集<br/>
            </th>
            <td><?= $this->Form->select('condition_id', $conditions, ['class' => 'form-control', 'empty' => true]) ?></td>
            <td rowspan="5" class="text-right">
                <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'statistics', 'action' => 'conditions', 'clear' => 1]) ?>">クリア</a>
            </td>
        </tr>
        <tr>
            <th rowspan="3">
                応募状況<br/>
                該当：<?= $users->count() ?>件
            </th>
            <th>学生</th>
            <td><?= $this->Form->multicheckbox('contact_state_student',Defines::CONTACT_STATES_ENGINEER,['multiple'=>'checkbox','default'=>[0,1,2,]])?></td>
        </tr>
        <tr>
            <th>企業</th>
            <td><?= $this->Form->multicheckbox('contact_state_company',Defines::CONTACT_STATES_COMPANY,['multiple'=>'checkbox','default'=>[0,1,2,]])?></td>
        </tr>
        <tr>
            <th>教師</th>
            <td><?= $this->Form->multicheckbox('contact_state_teacher',Defines::CONTACT_STATES_TEACHER,['multiple'=>'checkbox','default'=>[0,1,2,]])?></td>
        </tr>
        <tr>
            <th>
                スキル条件<br/>
                該当：<?= $skills->count() ?>件
            </th>
            <th>スキル分野</th>
            <td><?= $this->Form->select('field_id', $fields, ['empty' => '制限なし', 'class' => 'form-control']); ?></td>
        </tr>
    </tbody>
</table>