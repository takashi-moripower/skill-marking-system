<div class="row mb-1">
    <div class="col-2 pt-1">
        Skill <?= $i+1 ?>
    </div>
    <div class="col-6 p-0">
        <?= $this->Form->select("skill[{$i}][id]", $skills, ['class' => 'form-control', 'value' => $this->request->getData("skill.{$i}.id"),'empty'=>true]) ?>
    </div>
    <div class="col-4 pt-0 pb-0 pl-3">
        <?php foreach ($levels as $level): ?>
            <label class="d-inline-block mt-1">
                <?= $level ?>
                <?= $this->Form->checkbox("skill[{$i}][level][]", ['value' => $level, 'hiddenField' => false, 'checked' => in_array($level, (array) $this->request->getData("skill.{$i}.level"))]); ?>
            </label>
        <?php endforeach; ?>
    </div>
</div>
