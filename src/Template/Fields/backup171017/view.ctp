<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Field $field
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Field'), ['action' => 'edit', $field->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Field'), ['action' => 'delete', $field->id], ['confirm' => __('Are you sure you want to delete # {0}?', $field->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Fields'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Field'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Organizations'), ['controller' => 'Organizations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Organization'), ['controller' => 'Organizations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Parent Fields'), ['controller' => 'Fields', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Parent Field'), ['controller' => 'Fields', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="fields view large-9 medium-8 columns content">
    <h3><?= h($field->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($field->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Organization') ?></th>
            <td><?= $field->has('organization') ? $this->Html->link($field->organization->name, ['controller' => 'Organizations', 'action' => 'view', $field->organization->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Parent Field') ?></th>
            <td><?= $field->has('parent_field') ? $this->Html->link($field->parent_field->name, ['controller' => 'Fields', 'action' => 'view', $field->parent_field->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($field->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lft') ?></th>
            <td><?= $this->Number->format($field->lft) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rght') ?></th>
            <td><?= $this->Number->format($field->rght) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Fields') ?></h4>
        <?php if (!empty($field->child_fields)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Organization Id') ?></th>
                <th scope="col"><?= __('Parent Id') ?></th>
                <th scope="col"><?= __('Lft') ?></th>
                <th scope="col"><?= __('Rght') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($field->child_fields as $childFields): ?>
            <tr>
                <td><?= h($childFields->id) ?></td>
                <td><?= h($childFields->name) ?></td>
                <td><?= h($childFields->organization_id) ?></td>
                <td><?= h($childFields->parent_id) ?></td>
                <td><?= h($childFields->lft) ?></td>
                <td><?= h($childFields->rght) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Fields', 'action' => 'view', $childFields->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Fields', 'action' => 'edit', $childFields->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Fields', 'action' => 'delete', $childFields->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childFields->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Skills') ?></h4>
        <?php if (!empty($field->skills)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Field Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($field->skills as $skills): ?>
            <tr>
                <td><?= h($skills->id) ?></td>
                <td><?= h($skills->field_id) ?></td>
                <td><?= h($skills->name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Skills', 'action' => 'view', $skills->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Skills', 'action' => 'edit', $skills->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Skills', 'action' => 'delete', $skills->id], ['confirm' => __('Are you sure you want to delete # {0}?', $skills->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
