<?php

use App\Defines\Defines;
?>
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
        <?php foreach ($organizations as $organization): ?>
            <tr>
                <th>
                    <?= h($organization->path) ?>
                </th>
                <td class="text-right">
                    <?= $this->Html->link( $organization->count_engineers , ['controller' => 'engineers', 'action' => 'index', 'organization_id' => $organization->id, 'clear' => 1]) ?>
                </td>
                <td class="text-right">
                    <?= $this->Html->link( $organization->count_works , ['controller'=>'works','action'=>'index','organization_id'=>$organization->id,'clear'=>1] ) ?>
                </td>
                <td class="text-right">
                    <?= $this->Html->link( $organization->count_works_marked , ['controller'=>'works','action'=>'index','organization_id'=>$organization->id,'mark-state'=>Defines::MARK_STATE_MARKED,'clear'=>1] ) ?>
                </td>
                <td class="text-right">
                    <?= $this->Html->link( $organization->count_works_unmarked , ['controller'=>'works','action'=>'index','organization_id'=>$organization->id,'mark-state'=>Defines::MARK_STATE_UNMARKED,'clear'=>1] ) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
