<?php

use App\Defines\Defines;
?>
<hr/>
<h3 class="mt-4">所属している組織の状況</h3>
<table class="table mt-4" style="width:auto">
    <thead>
        <tr class="bg-light">
            <th>
                組織
            </th>
            <th>
                人数
            </th>
            <th>
                作品数
            </th>
            <th>
                採点済み
            </th>
            <th>
                未採点
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orgs as $org): ?>
            <tr>
                <th>
                    <?= $org->path_name ?>
                </th>
                <td class="text-right">
                    <?= $this->Html->link($org->count_engineers, ['controller' => 'engineers', 'action' => 'index', 'organization_id' => $org->id]) ?>
                </td>
                <td class="text-right">
                    <?= $this->Html->link($collections[$org->id]['all'], ['controller' => 'works', 'action' => 'index', 'organization_id' => $org->id, 'mark-state' => Defines::MARK_STATE_ALL]) ?>
                </td>
                <td class="text-right">
                    <?= $this->Html->link($collections[$org->id]['marked'], ['controller' => 'works', 'action' => 'index', 'organization_id' => $org->id, 'mark-state' => Defines::MARK_STATE_MARKED]) ?>
                </td>
                <td class="text-right">
                    <?= $this->Html->link($collections[$org->id]['unmarked'], ['controller' => 'works', 'action' => 'index', 'organization_id' => $org->id, 'mark-state' => Defines::MARK_STATE_UNMARKED]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
