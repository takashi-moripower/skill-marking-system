<?php
use App\Defines\Defines;
use App\Utility\MyUtil;
?>
<?= $this->Form->create(null, ['valueSources' => 'data', 'url' => ['controller' => 'statistics', 'action' => 'skills']]); ?>
<table class="table table-sm table-bordered ">
    <tbody>
        <tr>
            <th rowspan="3">
                作者条件<br/>
                該当：<?= $users->count() ?>名
            </th>
            <th>所属組織</th>
            <td><?= $this->Form->select('organization_id', $organizations, ['class' => 'form-control', 'empty' => '制限なし（システムに登録済みの全組織）']) ?></td>
            <td rowspan="5" class="text-right">
                <button class="btn btn-primary mr-2" type="submit"><i class="fa fa-search"></i> 検索</button>
                <a class="btn btn-outline-primary mr-2" href="<?= $this->Url->build(['controller' => 'statistics', 'action' => 'skills', 'clear' => 1]) ?>">クリア</a>
            </td>
        </tr>
        <tr>
            <th>年齢</th>
            <td>
                最低：<?= $this->Form->select('min_age', MyUtil::getAges(), ['class' => 'form-control d-inline-block w-20']); ?>　～
                最高：<?= $this->Form->select('max_age', MyUtil::getAges(), ['class' => 'form-control d-inline-block w-20']); ?>
            </td>
        </tr>
        <tr>
            <th>性別</th>
            <td>
                <?= $this->Form->select('sex', Defines::CONDITION_SEX_OPTIONS, ['class' => 'form-control d-inline-block w-20']) ?>
            </td>
        </tr>
        <tr>
            <th>
                作品条件<br/>
                該当：<?= $works->count() ?>件
            </th>
            <th>ジャンル</th>
            <td><?= $this->Form->select('junle_id', $junles, ['empty' => '制限なし', 'class' => 'form-control']); ?></td>
        </tr>
        <tr>
            <th>
                スキル条件<br/>
                該当：<?= $skills->count() ?>件
            </th>
            <th>スキル分野</th>
            <td><?= $this->Form->select('field_id', $fields, ['empty' => '制限なし', 'class' => 'form-control']); ?></td>
        </tr>
    </tbody>
</table>
<?= $this->Form->end() ?>

