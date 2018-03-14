<?php
use App\Defines\Defines;
?>
<?= Defines::TITLES[Defines::MODE_MARKING] ?>の登録手続きメールです

以下のリンクにアクセスして手続きを続けてください
<?= $this->Url->build(['controller'=>'registering-users','action'=>'validate',$user->token],1) ?>

このメールにお心当たりのない方は、そのまま破棄してください
