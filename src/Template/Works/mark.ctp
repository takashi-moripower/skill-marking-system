<?php

use App\Defines\Defines;

$loginUserId = $this->getLoginUser('id');
$loginUserGroup = $this->getLoginUser('group_id');

$LEVELS = range(1, Defines::SKILL_LEVEL_MAX);
$LEVELS = array_combine($LEVELS, $LEVELS);
?>

<div class="card">
    <div class="card-header">
        作品　採点
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">題名</th>
                    <td class=" border-top-0"><?= h($work->name) ?></td>
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
            </tbody>
            <tbody role="skills">
                <tr>
                    <th>作者の採点</th>
                    <td>
                        <?php
                        echo $this->Element('skills', ['skills' => $work->getSkillsBy($work->user_id) ,'user_id'=>$work->user_id]);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?= h($this->getLoginUser('name')) ?> 以外の採点</th>
                    <td>
                        <?php
                        echo $this->Element('skills', ['skills' => $work->getSkillsBy($loginUserId, 1)]);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?= h($this->getLoginUser('name')) ?> の採点</th>
                    <td>
                        <?php
                        $skills = $work->getSkillsBy($loginUserId);
                        foreach ($skills as $skill):
                            ?>
                            <div class="card bg-success text-white border-dark mb-1">
                                <div class="card-body p-1">
                                    <?= $this->Form->create(null, ['class' => 'form-inline clearfix form-edit', 'level-old' => $skill->level]); ?>
                                    <?= $this->Form->hidden('user_id', ['value' => $loginUserId]); ?>
                                    <?= $this->Form->hidden('work_id', ['value' => $work->id]); ?>
                                    <?= $this->Form->hidden('skill_id', ['value' => $skill->id]); ?>

                                    <?= $skill->field_path ?> > 
                                    <?= $skill->name ?> - 
                                    <?= $this->Form->select('level', $LEVELS, ['value' => $skill->level, 'class' => 'align-middle']) ?>
                                    <?= $this->Form->button('修正', ['class' => 'btn btn-outline-dark disabled btn-sm ml-auto bg-weak-white', 'disabled' => 'disabled', 'type' => 'submit', 'name' => 'action', 'value' => 'set']) ?>
                                    <?= $this->Form->button('削除', ['class' => 'btn btn-outline-danger btn-sm ml-1 bg-weak-white', 'type' => 'submit', 'name' => 'action', 'value' => 'delete']) ?>
                                    <?= $this->Form->end() ?>
                                </div>
                            </div>
                        <?php endforeach ?>
                        <div class="card bg-success border-dark text-white mb-1">
                            <div class="card-body p-1">
                                <?= $this->Form->create(null, ['class' => 'form-inline clearfix form-add']); ?>
                                <?= $this->Form->hidden('user_id', ['value' => $loginUserId]); ?>
                                <?= $this->Form->hidden('work_id', ['value' => $work->id]); ?>

                                <?= $this->Form->select('skill_id', $skillsToSet, ['value' => 0,'empty'=>true]) ?>
                                - 
                                <?= $this->Form->select('level', $LEVELS, ['value' => 1, 'class' => 'align-middle' ]) ?>
                                <?= $this->Form->button('追加', ['class' => 'btn btn-outline-dark disabled btn-sm ml-auto bg-weak-white', 'disabled' => 'disabled', 'type' => 'submit', 'name' => 'action', 'value' => 'set']) ?>
                                <?= $this->Form->end() ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody role="comments">
                <?php foreach ($work->comments as $comment): ?>
                    <?= $this->Element('works/comments', compact('comment', 'loginUserId')) ?>
                <?php endforeach ?>
                <tr>
                    <th>コメント</th>
                    <td>
                        <?= $this->Form->create(null, ['url' => ['controller' => 'comments', 'action' => 'add']]); ?>
                        <div class="row">
                            <div class="col-10">
                                <?= $this->Form->textArea('comment', ['class' => 'w-100', 'style' => 'height:4rem']) ?>

                            </div>
                            <div class="col-2 text-right">
                                <?= $this->Form->button('追加', ['class' => 'btn btn-outline-primary btn-sm']) ?>
                            </div>
                        </div>
                        <?= $this->Form->hidden('user_id', ['value' => $loginUserId]) ?>
                        <?= $this->Form->hidden('work_id', ['value' => $work->id]) ?>
                        <?= $this->Form->end() ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



<div class="text-right">

    <div class="text-right mt-1">
        <?php
        echo $this->Html->Link('一覧', ['controller' => 'works', 'action' => 'index'], ['class' => 'btn btn-outline-primary ml-1']);

        if (in_array($loginUserGroup, [Defines::GROUP_ADMIN, Defines::GROUP_ORGANIZATION_ADMIN, Defines::GROUP_ENGINEER])) {
            echo $this->Html->Link('編集', ['controller' => 'works', 'action' => 'edit', $work->id], ['class' => 'btn btn-outline-primary ml-1']);
        }
        ?>
    </div>
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
    $(function(){
        $('tbody[role="comments"]').on('click','button[value="delete"]',function(event){
            return confirm('realy delete?');
        });
        
    });
</script>
<?php $this->end(); ?>