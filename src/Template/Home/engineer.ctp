<?php
$loginUserId = $this->getLoginUser('id');
?>
<h2>技術者メニュー</h2>


<ul>
    <li><?= $this->Html->link('作品投稿',['controller'=>'works','action'=>'add'])?></li>
    <li><?= $this->Html->link('作品一覧',['controller'=>'works','action'=>'index'])?></li>
    <li><?= $this->Html->link('評価確認',['controller'=>'engineers','action'=>'view',$loginUserId])?></li>
</ul>