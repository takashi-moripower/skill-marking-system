<?php
$draw_row = function($aro, $depth) use(&$draw_row) {

    $w = $depth * 2 + 1;
    echo "<tr>";
    echo "<td style='padding-left:{$w}rem'>{$aro->model}-{$aro->foreign_key}</td>";
    echo "<td class='text-right'>{$aro->parent_id}</td>";
    echo "<td class='text-right'>{$aro->lft}</td>";
    echo "<td class='text-right'>{$aro->rght}</td>";
    echo "</tr>";

    if (empty($aro->children)) {
        return;
    }

    foreach ($aro->children as $child) {
        $draw_row($child, $depth + 1);
    }
};
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>model - foreign_key</th>
            <th>parent_id</th>
            <th>lft</th>
            <th>rght</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($arosTree as $aro): ?>
            <?php $draw_row($aro, 0); ?>
        <?php endforeach ?>
    </tbody>
</table>