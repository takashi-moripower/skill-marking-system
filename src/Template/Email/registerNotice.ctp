<?php 
use App\Defines\Defines;
?>

<?= Defines::TITLES[Defines::MODE_MARKING] ?>の登録手続きが完了しました

以下のアドレスからログインして、自己情報を登録してください
<?= $this->Url->build(['controller'=>'users','action'=>'login'],1) ?>

password:<?= $user['password'] ?>

このメールにお心当たりのない方は、そのまま破棄してください

