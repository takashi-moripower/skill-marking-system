<?php
use App\Defines\Defines;
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
        <?php foreach ($skills as $skill): ?>
            <tr>
                <th><?= $skill->label ?></th>
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
