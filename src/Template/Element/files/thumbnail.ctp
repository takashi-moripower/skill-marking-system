<?php

use App\Defines\Defines;

$fileSizeText = function($size) {
    if ($size > 1073741824) {
        return ($size >> 30) . "GB";
    }

    if ($size > 1048576) {
        return ($size >> 20) . "MB";
    }

    if ($size > 1024) {
        return ($size >> 10) . "kB";
    }

    return $size . "B";
};

$label = h($file->name);
$url = $this->Url->build(['controller' => 'files', 'action' => 'load', $file->id, $file->name]);

$ext = substr($file->name, strrpos($file->name, '.') + 1);
if (in_array(strtolower($ext), Defines::IMAGE_EXTRACTS)) {
    $label = "<img src='{$url}' class='thumbnail mr-1'/>" . $label;
};
?>

<div>
    <a href="<?= $url ?>">
        <?= $label ?>
    </a> (<?= $fileSizeText($file->size) ?>)
</div>
