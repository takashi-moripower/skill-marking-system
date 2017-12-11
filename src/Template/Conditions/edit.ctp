<?php

use App\Defines\Defines;

$this->Form->templates([
    'checkboxWrapper' => '<div class="checkbox d-inline-block px-2">{{label}}</div>',
]);
?>

<?= $this->Form->create($condition) ?>

<div class="card">
    <div class="card-header">
        人材募集条件　<?= ($this->request->action == 'edit') ? '編集' : '新規作成'; ?>
    </div>
    <div class="card-body p-0">
        <table class="table m-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td class="w-80 border-top-0"><?= $this->Form->control('title', ['label' => false, 'class' => 'w-100']); ?></td>
                </tr>
                <tr>
                    <th>説明</th>
                    <td><?= $this->Form->control('note', ['label' => false, 'class' => 'w-100']); ?></td>
                </tr>
                <tr>
                    <th>スキル</th>
                    <td>
                        <?= $this->Form->select('skill_id', $skills, ['empty' => true]) ?><?= $this->Form->select('skill_levels', range(1, Defines::SKILL_LEVEL_MAX), ['multiple' => 'checkbox']) ?>
                        <div class='float-right'>
                            <button class='btn btn-sm btn-outline-primary' type='button' name='add_skill'>追加</buttoN>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody name='skill_template'>
                <tr>
                    <th>スキル</th>
                    <td></td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

<div  class="text-right mt-2" >
    <?= $this->Form->button('保存', ['class' => 'btn btn-primary ml-1']) ?>
    <?= $this->Html->link('一覧に戻る', ['controller' => 'conditions', 'action' => 'index'], ['class' => 'btn btn-secondary  ml-1']) ?>
</div>
<?= $this->Form->end ?>

<?php $this->append('script'); ?>
<script>
    
    var SKILLS = JSON.parse('<?= json_encode( $skills ) ?>');
    $(function () {

        $(document).on('change', 'select[name="skill_id"]', OnChangeSkill);

        OnChangeSkill();
        
        function OnChangeSkill(event) {
            
            console.log( 'on change skill');
            if ($('select[name="skill_id"]').val() == 0) {
                $('button[name="add_skill"]').attr('disabled', 'disabled')
            }else{
                $('button[name="add_skill"]').removeAttr('disabled')
            }
        }
    });

</script>
<?php $this->end() ?>