<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\File $file
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit File'), ['action' => 'edit', $file->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete File'), ['action' => 'delete', $file->id], ['confirm' => __('Are you sure you want to delete # {0}?', $file->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Files'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New File'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Works'), ['controller' => 'Works', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Work'), ['controller' => 'Works', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="files view large-9 medium-8 columns content">
    <h3><?= h($file->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Work') ?></th>
            <td><?= $file->has('work') ? $this->Html->link($file->work->name, ['controller' => 'Works', 'action' => 'view', $file->work->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($file->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($file->id) ?></td>
        </tr>
    </table>
</div>
