<h3 class="mt-4">登録申請</h3>
<?php if ($count_users): ?>
    <div class="alert alert-success alert-dismissible fade show clearfix" role="alert">
        <p class="float-left">
            <?= $count_users ?>名のユーザーが登録申請しています
        </p>
        <div class="float-right">
            <?= $this->Html->link('<i class="fa fa-hand-o-right"></i> 承認処理', ['controller' => 'RegisteringUsers', 'action' => 'index'], ['class' => 'btn btn-success', 'escape' => false]) ?>
        </div>
    </div>
<?php else: ?>
    <p>ユーザーの登録申請はありません</p>
<?php endif; ?>
