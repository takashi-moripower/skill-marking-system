<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Field[]|\Cake\Collection\CollectionInterface $fields
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Field'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Organizations'), ['controller' => 'Organizations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Organization'), ['controller' => 'Organizations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="fields index large-9 medium-8 columns content">
    <h3><?= __('Fields') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('organization_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('parent_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fields as $field): ?>
            <tr>
                <td><?= $this->Number->format($field->id) ?></td>
                <td><?= h($field->name) ?></td>
                <td><?= $field->has('organization') ? $this->Html->link($field->organization->name, ['controller' => 'Organizations', 'action' => 'view', $field->organization->id]) : '' ?></td>
                <td><?= $field->has('parent_field') ? $this->Html->link($field->parent_field->name, ['controller' => 'Fields', 'action' => 'view', $field->parent_field->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $field->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $field->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $field->id], ['confirm' => __('Are you sure you want to delete # {0}?', $field->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
