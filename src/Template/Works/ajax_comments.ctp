<?php
$loginUserId = $this->getLoginUser('id');
?>
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
                        <div class="row">
                            <div class="col-10">
                                <?= $this->Form->textArea('new-comment', ['class' => 'w-100', 'style' => 'height:4rem']) ?>
                            </div>
                            <div class="col-2 text-right">
                                <?= $this->Form->button('追加', ['class' => 'btn btn-outline-primary btn-sm btn-add-comment', 'type' => 'button']) ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

