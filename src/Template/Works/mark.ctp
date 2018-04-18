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
                    <th>所属組織</th>
                    <td><?= $this->element('organizations/list', ['organizations' => $work->user->organizations]) ?></td>
                </tr>
                <tr>
                    <th>解説</th>
                    <td><?= MyUtil::strip_tags($work->note) ?></td>
                </tr>
                <?php if (!empty($work->files)): ?>
                    <tr>
                        <th>添付ファイル</th>
                        <td>
                            <?php foreach ($work->files as $file): ?>
                                <?= $this->Element('files/thumbnail', ['file' => $file]) ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<section name="ajax-skills">
</section>
<section name="ajax-comments">
</section>
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
<?php $this->append('script') ?>
<script>
    var URL_AJAX_SKILLS = '<?= $this->Url->build(['controller' => 'works', 'action' => 'ajax_skills',$work->id]); ?>';
    var URL_AJAX_COMMENTS = '<?= $this->Url->build(['controller' => 'works', 'action' => 'ajax_comments',$work->id]); ?>';
    var loginUserId = <?= $loginUserId ?>;
    var work_id = <?= $work->id ?>;
    $(function () {

        updateSkills(null);
        updateNewSkill(null);
        updateComments(null);

        $(document).on('click', '.btn[skill_id][level]', postSkill);
        $(document).on('change', 'select[name="new_skill_id"]', updateNewSkill);
        $(document).on('click', '.btn.btn-add-comment', addComment);
        $(document).on('click', '.btn.btn-edit-comment', editComment);
        $(document).on('click', '.btn.btn-delete-comment', deleteComment);


        function editComment(e) {
            var comment_id = $(e.currentTarget).attr('comment_id');
            var comment = $('textarea[comment_id="' + comment_id + '"]').val();
            var data = {
                id: comment_id,
                work_id: work_id,
                user_id: loginUserId,
                comment: comment
            };

            updateComments(data);
        }

        function deleteComment(e) {
            var comment_id = $(e.currentTarget).attr('comment_id');

            var data = {
                id: comment_id,
                work_id: work_id,
                user_id: loginUserId,
                comment: null
            };

            updateComments(data);
        }

        function addComment(e) {
            var comment = $('textarea[name="new-comment"]').val();
            var data = {
                work_id: work_id,
                user_id: loginUserId,
                comment: comment
            };
            updateComments(data);
        }


        function postSkill(e) {
            var skill_id = $(e.currentTarget).attr('skill_id');
            var level = $(e.currentTarget).attr('level');
            var data = {
                work_id: work_id,
                skill_id: skill_id,
                user_id: loginUserId,
                level: level
            };
            updateSkills({data: data, method: "post"});
        }

        function updateSkills(data) {
            update(URL_AJAX_SKILLS,data,"ajax-skills");
        }

        function updateComments(data) {
            update(URL_AJAX_COMMENTS,data,"ajax-comments");
        }

        function update(url, data, section_name) {
            var data_post;
            if (data !== null) {
                data_post = {
                    data: data,
                    method: 'post'
                };
            } else {
                data_post = null;
            }

            $.ajax(url, data_post)
                    .done(function (result) {
                        $('section[name="' + section_name + '"]').html(result);
                    });
        }

        function updateNewSkill(e) {
            var skill_id = $('select[name="new_skill_id"]').val();
            console.log(skill_id);

            var btn = $('.new-skill .btn.btn-skill-selector');
            if (skill_id == '') {
                btn.attr('disabled', 'disabled');
                btn.removeAttr('skill_id');
            } else {
                btn.removeAttr('disabled');
                btn.attr('skill_id', skill_id);
            }
        }


    });
</script>
<?php $this->end(); ?>