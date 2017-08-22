<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Junle $junle
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Junle'), ['action' => 'edit', $junle->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Junle'), ['action' => 'delete', $junle->id], ['confirm' => __('Are you sure you want to delete # {0}?', $junle->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Junles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Junle'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Works'), ['controller' => 'Works', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Work'), ['controller' => 'Works', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="junles view large-9 medium-8 columns content">
    <h3><?= h($junle->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($junle->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($junle->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Works') ?></h4>
        <?php if (!empty($junle->works)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Note') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($junle->works as $works): ?>
            <tr>
                <td><?= h($works->id) ?></td>
                <td><?= h($works->user_id) ?></td>
                <td><?= h($works->name) ?></td>
                <td><?= h($works->note) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Works', 'action' => 'view', $works->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Works', 'action' => 'edit', $works->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Works', 'action' => 'delete', $works->id], ['confirm' => __('Are you sure you want to delete # {0}?', $works->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
