<?php
$edit = ($comment->user_id == $loginUserId);
?>
<tr>
    <th>
        <?= $comment->user->name ?> からのコメント
    </th>
    <td>
        <?php if ($edit): ?>
            <?= $this->Form->create($comment, ['url' => ['controller' => 'comments', 'action' => 'edit']]) ?>
            <?= $this->Form->hidden('id') ?> 
            <?= $this->Form->hidden('work_id') ?> 
            <div class="row">
                <div class="col-10">
                    <?= $this->Form->textArea('comment', ['class' => 'w-100', 'style' => 'height:4rem']) ?>
                </div>
                <div class="col-2 text-right">
                    <button class="btn btn-small btn-outline-primary btn-sm" name="action" value="edit">
                        編集
                    </button>
                    <button class="btn btn-small btn-outline-danger btn-sm" name="action" value="delete">
                        削除
                    </button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        <?php else: ?>
            <?= $comment->comment ?>
        <?php endif; ?>
    </td>
</tr>
