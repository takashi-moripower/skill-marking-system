<?php

use App\Defines\Defines;

$loginUserId = $this->getLoginUser('id');
$loginUserGroup = $this->getLoginUser('group_id');

$LEVELS = range(1, Defines::SKILL_LEVEL_MAX);
$LEVELS = array_combine($LEVELS, $LEVELS);

?>

<table class="table table-bordered">
    <tbody>
        <tr>
            <th class="w-25">題名</th>
            <td><?= h($work->name) ?></td>
        </tr>
        <tr>
            <th>ジャンル</th>
            <td>
                <?php foreach ($work->junles as $junle): ?>
                    <?= h($junle->name) ?>
                    <?= ($junle !== end($work->junles)) ? ',' : '' ?>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <th>投稿者</th>
            <td><?= h($work->user->name) ?></td>
        </tr>
        <tr>
            <th>解説</th>
            <td><?= nl2br(h($work->note)) ?></td>
        </tr>
        <tr>
            <th>添付ファイル</th>
            <td>
                <?php foreach ($work->files as $file): ?>
                    <?= $this->Element('files/thumbnail', ['file' => $file]) ?>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <th>スキル<br/>作者による評価)</th>
            <td>
                <?php
                echo $this->Element('skills', ['skills' => $skillsBySelf]);
                ?>
            </td>
        </tr>
        <tr>
            <th>スキル<br/>（<?= h($this->getLoginUser('name')) ?> 以外による評価)</th>
            <td>
                <?php
                echo $this->Element('skills', ['skills' => $skillsByOther]);
                ?>
            </td>
        </tr>
        <tr>
            <th>スキル<br/>（<?= h($this->getLoginUser('name')) ?> による評価)</th>
            <td>
                <?php
                $skills = $skillsByMe;
                foreach ($skills as $skill):
                    ?>
                    <div class="card bg-light mb-1">
                        <div class="card-body p-1">
                            <?= $this->Form->create(null, ['class' => 'form-inline clearfix form-edit', 'level-old' => $skill->level]); ?>
                            <?= $this->Form->hidden('user_id', ['value' => $loginUserId]); ?>
                            <?= $this->Form->hidden('work_id', ['value' => $work->id]); ?>
                            <?= $this->Form->hidden('skill_id', ['value' => $skill->id]); ?>

                            <?= $skill->path ?> > 
                            <?= $skill->name ?> - 
                            <?= $this->Form->select('level', $LEVELS, ['value' => $skill->level, 'class' => 'align-middle']) ?>
                            <?= $this->Form->button('修正', ['class' => 'btn btn-outline-dark disabled btn-sm ml-auto', 'disabled' => 'disabled', 'type' => 'submit', 'name' => 'action', 'value' => 'set']) ?>
                            <?= $this->Form->button('削除', ['class' => 'btn btn-outline-danger btn-sm ml-1', 'type' => 'submit', 'name' => 'action', 'value' => 'delete']) ?>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                <?php endforeach ?>
                <div class="card bg-light mb-1">
                    <div class="card-body p-1">
                        <?= $this->Form->create(null, ['class' => 'form-inline clearfix form-add']); ?>
                        <?= $this->Form->hidden('user_id', ['value' => $loginUserId]); ?>
                        <?= $this->Form->hidden('work_id', ['value' => $work->id]); ?>

                        <?= $this->Form->select('skill_id', $skillsToSet, ['value' => 0]) ?>
                        - 
                        <?= $this->Form->select('level', $LEVELS, ['value' => 1, 'class' => 'align-middle']) ?>
                        <?= $this->Form->button('追加', ['class' => 'btn btn-outline-dark disabled btn-sm ml-auto', 'disabled' => 'disabled', 'type' => 'submit', 'name' => 'action', 'value' => 'set']) ?>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<div class="text-right">
    <?php
    if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN, Defines::GROUP_MARKER])) {
        echo $this->Html->Link('作品一覧へ', ['controller' =>'works','action'=>'index'],['class'=>'btn btn-outline-primary']);
    }
    ?>
</div>
<?php $this->append('script'); ?>
<script>
    $(function () {
        $('form.form-edit').on('change', 'select[name="level"]', function (event) {
            form = $(event.target).parents('form.form-edit');
            level = form.find('select[name="level"]').val();
            levelOld = form.attr('level-old');

            button = form.find('button[value="set"]');

            setButton(button, level == levelOld);

        });

        $('form.form-add').on('change', 'select[name="skill_id"]', function (event) {
            form = $(event.target).parents('form.form-add');
            skill_id = form.find('select[name="skill_id"]').val();
            button = form.find('button[value="set"]')

            setButton(button, skill_id == '0');

        });

        function setButton(button, state) {
            if (state) {
                button.attr('disabled', 'disabled');
                button.removeClass('btn-outline-primary');
                button.addClass('btn-outline-dark disabled');
            } else {
                button.removeAttr('disabled');
                button.addClass('btn-outline-primary');
                button.removeClass('btn-outline-dark disabled');
            }
        }
    });
</script>
<?php $this->end(); ?>