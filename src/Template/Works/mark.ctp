<?php

use App\Defines\Defines;
use App\Utility\MyUtil;

$loginUserId = $this->getLoginUser('id');
$loginUserGroup = $this->getLoginUser('group_id');
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
                    <td><?= MyUtil::strip_tags($work->note) ?></td>
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
        </table>
    </div>
</div>
<div class="card mt-1">
    <div class="card-body p-0">
        <table class="table mb-0">
            <tbody role="skills">
                <tr>
                    <th class="w-20 border-top-0">
                        作者の採点
                    </th>
                    <td class=" border-top-0">
                        <?php
                        echo $this->Element('skills/colored_skills', ['skills' => $work->getSkillsBy($work->user_id), 'user_id' => $work->user_id]);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?= h($this->getLoginUser('name')) ?> 以外の採点</th>
                    <td>
                        <?php
                        echo $this->Element('skills/colored_skills', ['skills' => $work->getSkillsBy($loginUserId, 1)]);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?= h($this->getLoginUser('name')) ?> の採点
                        <?= $this->Element('popup_hint',['message'=>'スキルレベルボタンをクリックすると即座に情報は保存されます'])?>
                    </th>
                    <td>
                        <?php
                        $skills = $work->getSkillsBy($loginUserId);
                        foreach ($skills as $skill):
                            echo $this->Element('works/loginUserMark', compact('skill', 'loginUserId'));
                        endforeach;
                        echo $this->Element('works/newMark', compact('loginUserId'));
                        ?>

                        <?php ?>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="card mt-1">

    <div class="card-boty p-0">
        <table class="table mb-0">
            <tbody role="comments">
                <?php foreach ($work->comments as $comment): ?>
                    <?= $this->Element('works/comments', compact('comment', 'loginUserId')) ?>
                <?php endforeach ?>
                <tr>
                    <th class="w-20 border-top-0">コメント</th>
                    <td class="border-top-0">
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
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->Form->end() ?>


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

    var skillUpdated = '<?= isset($skillUpdated) ? $skillUpdated : null; ?>';

    $(function () {
        if (skillUpdated !== '') {
            $("html,body").scrollTop($('tbody[role="skills"]').offset().top);

            console.log('focus');
        }


        $('form.form-add').on('change', 'select[name="skill_id"]', function (event) {
            form = $(event.target).parents('form.form-add');
            skill_id = form.find('select[name="skill_id"]').val();
            if( skill_id === ''){
                $('form.form-add .btn.btn-skill-selector').attr('disabled','disabled');
            }else{
                $('form.form-add .btn.btn-skill-selector').removeAttr('disabled');
            }

        });

        $('tbody[role="comments"]').on('click', 'button[value="delete"]', function (event) {
            return confirm('realy delete?');
        });

    });
</script>
<?php $this->end(); ?>