<?php
$edit = ($comment->user_id == $loginUserId);
?>
<tr>
    <th>
        <?= $comment->user->name ?> からのコメント
    </th>
    <td>
        <?php if ($edit): ?>
            <div class="row">
                <div class="col-10">
                    <?= $this->Form->textArea('comment', ['class' => 'w-100', 'style' => 'height:4rem', 'comment_id' => $comment->id, 'value' => $comment->comment]) ?>
                </div>
                <div class="col-2 text-right">
                    <button class="btn btn-outline-primary btn-sm btn-edit-comment" type="button "comment_id="<?= $comment->id ?>">
                        編集
                    </button>
                    <button class="btn btn-outline-danger btn-sm btn-delete-comment" type="button "comment_id="<?= $comment->id ?>">
                        削除
                    </button>
                </div>
            </div>
        <?php else: ?>
            <?= $comment->comment ?>
        <?php endif; ?>
    </td>
</tr>
