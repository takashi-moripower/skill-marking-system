<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $junle->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $junle->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Junles'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Works'), ['controller' => 'Works', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Work'), ['controller' => 'Works', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="junles form large-9 medium-8 columns content">
    <?= $this->Form->create($junle) ?>
    <fieldset>
        <legend><?= __('Edit Junle') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('works._ids', ['options' => $works]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
