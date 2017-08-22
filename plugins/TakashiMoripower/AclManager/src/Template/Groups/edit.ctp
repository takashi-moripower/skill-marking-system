<div class="card">
    <div class="card-header">
        Edit Group
    </div>
    <div class="card-body">
        <?= $this->Form->create($group) ?>


        <div class="form-group">
            <?= $this->Form->input('name',['class'=>'form-control']) ?>
        </div>

        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
