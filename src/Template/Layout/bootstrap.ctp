<?php

use App\Defines\Defines;
use Cake\Utility\Hash;

//$mode = $this->request->session()->read('App.Mode');
$mode = $this->getMode();
$this->start('title');
echo Hash::get(Defines::TITLES, $mode, Defines::TITLES[Defines::MODE_MARKING]);
$this->end();

if ($mode == Defines::MODE_MATCHING) {
    $style = "style2.css";
} else {
    $style = "style.css";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('icon') ?>

        <?php if (false): ?>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>  
        <?php endif ?>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

        <script src="https://use.fontawesome.com/763feb1343.js"></script>
        <?php
        ?>
        <?= $this->fetch('meta') ?>
        <?= $this->Html->css($style . '?v=' . $this->TimeStamp->TimeStamp("css/{$style}")) ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
        <?= $this->Html->script('common') ?>
    </head>
    <body class="<?= $this->bodyClass() ?>">
        <?= $this->element('header'); ?>
        <?= $this->Element('nav') ?>

        <div class="container">
            <?= $this->Element('flash') ?>
            <?= $this->fetch('content') ?>
        </div>
        <?= $this->element('footer'); ?>
    </body>
</html>
