<?php

use App\Defines\Defines;
use Cake\Routing\Router;

$location_valid = isset($condition->location);
$date_valid = isset($condition->dateStart);

$loginUserId = $this->getLoginUser('id');

$url = Router::url(null, true);
?>
<div class="card">
    <div class="card-header">
        <div class="h4 m-0">
            人材募集条件　
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table m-0">
            <tbody>
                <tr>
                    <th class="w-20 border-top-0">名称</th>
                    <td class="w-80 border-top-0"><?= h($condition->title) ?></td>
                </tr>
                <tr>
                    <th>説明</th>
                    <td><?= h($condition->note) ?></td>
                </tr>
            </tbody>
            <tbody class="option<?= $location_valid ? '' : ' d-none' ?>" role="location" option_type="1">
                <tr>
                    <th>開催地</th>
                    <td>
<?= h($condition->location) ?>
                    </td>
                </tr>
            </tbody>
            <tbody class="option<?= $date_valid ? '' : ' d-none' ?>" role="date" option_type="2">
                <tr>
                    <th>期間</th>
                    <td>
                        <?php
                        $date_start = DateTime::createFromFormat('Y-m-d', $condition->date_start);
                        $date_end = DateTime::createFromFormat('Y-m-d', $condition->date_end);
                        ?>
<?= $date_start->format('Y年m月d日') ?> - <?= $date_end->format('Y年m月d日') ?>
                    </td>
                </tr>
            </tbody>
            <tbody class="skills">
                <tr>
                    <th>スキル条件</th>
                    <td>
                        <?php
                        foreach ((array) $condition->skills as $skill):
                            echo $this->Element('conditions/index_skill', compact('skill'));
                        endforeach
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="text-right mt-2">
    <?php if ($condition->contacts): ?>
        <button class="btn btn-sm btn-primary">コンタクト要求済</button>
    <?php else: ?>
        <?= $this->Form->create(null, ['url' => ['controller' => 'contacts', 'action' => 'add']]) ?>
        <?= $this->Form->hidden('user_id', ['value' => $loginUserId]) ?>
        <?= $this->Form->hidden('condition_id', ['value' => $condition->id]) ?>
        <?= $this->Form->hidden('callback_url', ['value' => $url]) ?>
        <?= $this->Form->hidden('flags', ['value' => Defines::CONTACT_FLAG_FROM_ENGINEER]) ?>
        <button class="btn btn-sm btn-outline-primary">コンタクト要求</button>
        <?= $this->Form->end(); ?>
<?php endif; ?>
</div>
