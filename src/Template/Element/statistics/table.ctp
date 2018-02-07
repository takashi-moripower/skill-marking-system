<?php

use App\Defines\Defines;
use App\Utility\Color;
?>
<table class="table table-bordered table-sm">
    <tbody>
        <tr>
            <th>スキル名</th>
            <?php for ($l = 1; $l <= Defines::SKILL_LEVEL_MAX; $l++): ?>
                <th><?= $l ?></th>
            <?php endfor; ?>
            <th>計</th>
            <th>スキル平均</th>
        </tr>
        <?php
        $lineId = 0;
        $count = $skills->count();
        ?>
        <?php foreach ($skills as $skill): ?>
            <tr>
                <th>
                    <button class="line-chart-selector btn btn-outline-dark btn-sm" line_id="<?= $lineId ?>" line_hidden="0" type="button">
                        <?php
                        $H = $lineId * 360 / $count;
                        $color = Color::hsv($H, 1, 1);
                        $lineId++;
                        ?>
                        <div style="display:inline-block;width:4rem;height:1rem;border:3px solid rgba(<?= $color->r ?>,<?= $color->g ?>,<?= $color->b ?>,1);background-color: rgba(<?= $color->r ?>,<?= $color->g ?>,<?= $color->b ?>,0.1)"></div>
                        <?= $skill->label ?>
                    </button>
                </th>
                <?php for ($l = 1; $l <= Defines::SKILL_LEVEL_MAX; $l++): ?>
                    <td class="text-right">
                        <?php
                        $c = "count_{$l}";
                        echo $skill->{$c};
                        ?>
                    </td>
                <?php endfor; ?>
                <td class="text-right">
                    <?= $skill->count ?>
                </td>
                <td class="text-right">
                    <?= number_format($skill->average, 2) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
