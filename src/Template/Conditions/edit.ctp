<?php

use App\Defines\Defines;
use App\Utility\MyUtil;

$skillDefault = (object) [
            'id' => 0,
            'name' => '',
            '_joinData' => (object) ['levels' => 0]
];

$this->Form->templates(
        Defines::FORM_TEMPLATE_INLINE_CHECKBOX + Defines::FORM_TEMPLATE_RADIO
);
?>

<?= $this->Form->create($condition, ['name' => 'main']) ?>

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
                    <th>
                        公開
                        <?= $this->Element('popup_hint', ['message' => '非公開にすると、学生視点の人材募集条件一覧には表示されず、学生からは応募できなくなります。主催者側から勧誘することは可能です']) ?>
                    </th>
                    <td><?= $this->Form->radio('published', Defines::CONDITION_PUBLISHED_STATE); ?></td>
                </tr>
                <tr>
                    <th>説明</th>
                    <td><?= $this->Form->control('note', ['label' => false, 'class' => 'w-100', 'id' => 'editor']); ?></td>
                </tr>
            </tbody>
            <tbody class="skills">
                <?php foreach ((array) $condition->skills as $skill): ?>
                    <?= $this->Element('conditions/skill_row', ['skill' => $skill]); ?>
                <?php endforeach ?>
            </tbody>
            <tbody class="option<?= (isset($condition->max_age) || isset($condition->min_age)) ? '' : ' d-none' ?>" role="date" option_type="<?= Defines::CONDITION_OPTION_TYPE_MAX_AGE ?>">
                <tr>
                    <th>
                        年齢
                        <?= $this->Element('popup_hint', ['message' => '開催日とは関係なく、<br/>検索実行時の年齢で絞り込みます']) ?>
                    </th>
                    <td>
                        <div class="row">
                            <div class="col-10">
                                最低:
                                <?= $this->Form->select('min_age', MyUtil::getAges()); ?>
                                ～
                                最高:
                                <?= $this->Form->select('max_age', MyUtil::getAges()); ?>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" name="remove_option" class="btn btn-sm btn-outline-danger">削除</button>
                            </div>
                        </div>

                    </td>
                </tr>
            </tbody>
            <tbody class="option<?= isset($condition->sex) ? '' : ' d-none' ?>" role="date" option_type="<?= Defines::CONDITION_OPTION_TYPE_SEX ?>">
                <tr>
                    <th>性別</th>
                    <td>
                        <div class="row">
                            <div class="col-10">
                                <?= $this->Form->radio('sex', Defines::CONDITION_SEX_OPTIONS, ['default' => Defines::SEX_INDIFFARENCE]); ?>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" name="remove_option" class="btn btn-sm btn-outline-danger">削除</button>
                            </div>
                        </div>

                    </td>
                </tr>
            </tbody>
            <tbody class="option<?= isset($condition->date_start) ? '' : ' d-none' ?>" role="date" option_type="<?= Defines::CONDITION_OPTION_TYPE_DATE_START ?>">
                <tr>
                    <th>期間</th>
                    <td>
                        <div class="row">
                            <div class="col-10">
                                <?= $this->Form->input('date_start', ['type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => Defines::FORM_TEMPLATE_DATE + Defines::FORM_TEMPLATE_INLINE_CONTAINER]); ?> ～ 
                                <?= $this->Form->input('date_end', ['type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => Defines::FORM_TEMPLATE_DATE + Defines::FORM_TEMPLATE_INLINE_CONTAINER]); ?>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" name="remove_option" class="btn btn-sm btn-outline-danger">削除</button>
                            </div>
                        </div>

                    </td>
                </tr>
            </tbody>           
            <tbody class="option<?= isset($conditon->location) ? '' : ' d-none' ?>" role="location" option_type="<?= Defines::CONDITION_OPTION_TYPE_LOCATION ?>">
                <tr>
                    <th>開催地</th>
                    <td>
                        <div class="row">

                            <div class="col-10">
                                <?= $this->Form->input('location', ['type' => 'text', 'label' => false, 'class' => 'w-100']); ?>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" name="remove_option" class="btn btn-sm btn-outline-danger">削除</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody class="new-skill">
                <tr>
                    <th>条件を追加</th>
                    <td>
                        <div class="text-right">
                            <?= $this->Form->select('option_type', Defines::CONDITION_OPTIONS, ['empty' => false, 'default' => Defines::CONDITION_OPTION_TYPE_SKILL]) ?>
                            <button class='btn btn-sm btn-outline-primary' type='button' name='add_option'>条件を追加</button>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody  class="d-none skill_template">
                <?= $this->Element('conditions/skill_row', ['skill' => $skillDefault]); ?>
            </tbody>
        </table>
    </div>
</div>

<div  class="text-right mt-2" >
    <?= $this->Form->button('保存', ['class' => 'btn btn-primary ml-1', 'type' => 'button', 'name' => 'save']) ?>
    <?= $this->Html->link('一覧に戻る', ['controller' => 'conditions', 'action' => 'index'], ['class' => 'btn btn-secondary  ml-1']) ?>
</div>
<?= $this->Form->end ?>


<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">条件を追加</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <?= $this->Form->select('skill_id', $skills, ['empty' => true, 'class' => 'w-100']) ?>
                </div>
                <div class="text-right mt-2">
                    <?= $this->Form->select('skill_levels', MyUtil::getSkillLevels(), ['multiple' => 'checkbox']) ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="add_skill">スキル追加</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>
<script>
    // エディタへの設定を適用する
    CKEDITOR.replace('editor', {
        uiColor: '#EEEEEE',
        height: 200
    });
</script>

<?php $this->append('script') ?>
<script src="https://cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script>
<?php $this->end() ?>

<?php $this->append('script'); ?>
<script>

    var SKILLS = JSON.parse('<?= json_encode($skills) ?>');
    var CONDITION_OPTION_TYPE_DATE_START = <?= Defines::CONDITION_OPTION_TYPE_DATE_START ?>;
    var CONDITION_OPTION_TYPE_LOCATION = <?= Defines::CONDITION_OPTION_TYPE_LOCATION ?>;
    var CONDITION_OPTION_TYPE_SEX = <?= Defines::CONDITION_OPTION_TYPE_SEX ?>;
    var CONDITION_OPTION_TYPE_MAX_AGE = <?= Defines::CONDITION_OPTION_TYPE_MAX_AGE ?>;
    var CONDITION_OPTION_TYPE_SKILL = <?= Defines::CONDITION_OPTION_TYPE_SKILL ?>;

    $(function () {

        $('[data-toggle="tooltip"]').tooltip();


        $(document).on('change', 'select[name="skill_id"]', onChangeSkill);
        $(document).on('change', 'input[name="skill_levels[]"]', onChangeSkill);
        $(document).on('click', 'button[name="add_skill"]', onAddSkill);
        $(document).on('click', 'button[name="delete_skill"]', onDeleteSkill);
        $(document).on('click', 'button[name="save"]', onSave);
        $(document).on('click', 'button[name="add_option"]', onAddOption);
        $(document).on('click', 'button[name="remove_option"]', onRemoveOption);

        onChangeSkill();
        updateSkillSelector();
        updateConditionSelector();

        function onRemoveOption(event) {
            $(event.currentTarget).parents('tbody.option').addClass('d-none');
        }

        function onAddOption(event) {
            var type = $('select[name="option_type"]').val();

            if (type === '0') {
                openDialog();
                return;
            }

            $('tbody[option_type="' + type + '"]').removeClass('d-none');
            updateConditionSelector();
        }

        function updateConditionSelector() {
            update(CONDITION_OPTION_TYPE_DATE_START);
            update(CONDITION_OPTION_TYPE_LOCATION);
            update(CONDITION_OPTION_TYPE_SEX);
            update(CONDITION_OPTION_TYPE_MAX_AGE);

            $('select[name="option_type"]').val(CONDITION_OPTION_TYPE_SKILL);

            function update(type) {
                var tbody = $('tbody[option_type="' + type + '"]');
                var option = $('select[name="option_type"] option[value="' + type + '"]');
                if (tbody.hasClass('d-none')) {
                    option.removeAttr('disabled');
                } else {
                    option.attr('disabled', 'disabled');
                }
            }
        }

        function onChangeSkill(event) {
            updateAddButton();
        }

        function updateAddButton() {
            if ($('select[name="skill_id"]').val() == 0 || $('input[name="skill_levels[]"]:checked').length == 0) {
                $('button[name="add_skill"]').attr('disabled', 'disabled');
            } else {
                $('button[name="add_skill"]').removeAttr('disabled');
            }
        }

        function onAddSkill(event) {
            var id = $('select[name="skill_id"]').val();
            var name = SKILLS[id];
            var levels = $('input[name="skill_levels[]"]:checked').map(function () {
                return $(this).val();
            }).toArray();

            var template = $('tbody.skill_template').html();
            $('tbody.skills').append(template);

            var new_skill = $('tbody.skills tr:last-child');

            new_skill.find('.skill_name').text(name);
            new_skill.find('input[name^=skill_and_levels]').each(function (i, input) {
                var old_name = $(input).attr('name');
                var new_name = old_name.replace('[0]', '[' + id + ']');
                $(input).attr('name', new_name);

                var old_id = $(input).attr('id');
                if (old_id) {
                    var new_id = old_id.replace('-0-', '-' + id + '-');
                    $(input).attr('id', new_id);

                    $('label[for="' + old_id + '"]').attr('for', new_id);
                }

                if ($.inArray($(input).attr('value'), levels) != -1) {
                    $(input).prop('checked', true);
                }
            });


            updateSkillSelector();
            $('.modal').modal('hide');
        }

        function updateSkillSelector() {
            skill_used = $('input[name^="skill_and_levels"').map(function () {
                return $(this).attr('skill_id');
            }).toArray();

            $('select[name="skill_id"] option').each(function (i, op) {

                var skill_id = String($(op).attr('value'));

                if ($.inArray(skill_id, skill_used) === -1) {
                    $(op).removeAttr('disabled');
                } else {
                    $(op).attr('disabled', 'disabled');
                }
            });

            $('select[name="skill_id"]').val("");
            $('input[name^="skill_levels"').prop('checked', false);
            updateAddButton();
        }

        function onDeleteSkill(event) {
            tr = $(event.currentTarget).parents('tr');
            tr.remove();
            updateSkillSelector();
        }

        function onSave(event) {
            $('tbody.skill_template input').attr('disabled', 'disabled');
            $('tbody.option.d-none').find('input,select').attr('disabled', 'disabled');
            $('select[name="condition_type"]').attr('disabled', 'disabled');

            console.log($('form[name="main"]'));
            $('form[name="main"]').submit();
        }

        function openDialog(event) {
            $('.modal').modal();
        }
    });

</script>
<?php $this->end() ?>
