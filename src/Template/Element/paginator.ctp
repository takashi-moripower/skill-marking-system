<div class="paginator">
    <ul class="pagination justify-content-center">
        <?= $this->Paginator->first('<i class="fa fa-angle-double-left"></i>',['escape'=>false]) ?>
        <?= $this->Paginator->prev('<i class="fa fa-angle-left"></i>',['escape'=>false]) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('<i class="fa fa-angle-right"></i>',['escape'=>false]) ?>
        <?= $this->Paginator->last('<i class="fa fa-angle-double-right"></i>',['escape'=>false]) ?>
    </ul>
    <p class="text-right">
        <?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?>
    </p>
</div>
