
<?= $this->Form->create(NULL) ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>controller</th>
            <th>action</th>
            <?php foreach ($groups as $group): ?>
                <th class="group" group_id="<?= $group->id ?>">
                    <?= $group->name ?>
                </th>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($paths as $path): ?>
            <?php foreach ($path as $controller => $actions): ?>
                <?php
                if ($controller == 'App') {
                    continue;
                }
                $count = count($actions);
                $th_controller = "<th rowspan='{$count}' class='controller' controller='{$controller}'>{$controller}</th>";
                foreach ($actions as $action):
                    ?>
                    <tr>
                        <?php
                        echo $th_controller;
                        $th_controller = NULL;
                        ?>
                        <th class="action" action="<?= $controller ?>-<?= $action ?>" controller="<?= $controller ?>"><?= $action ?>
                            <?php foreach ($groups as $group): ?>
                            <td class="data text-center" group_id='<?= h($group->id) ?>' controller='<?= $controller ?>' action='<?= $controller ?>-<?= $action ?>' >
                                <?php
                                $val = $group->check($controller, $action);
                                echo $this->Form->checkbox($group->id . '.App/' . $controller . '/' . $action, ['value' => 1, 'default' => $val]);
                                ?>
                            </td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            <?php endforeach ?>
        <?php endforeach ?>
    </tbody>
</table>
<div class="text-right mb-5">
    <?= $this->Form->submit('保存',['class'=>'btn btn-primary']) ?>
</div>
<?= $this->Form->end() ?>
<?php $this->append('css') ?>
<style>
    table.table thead th{
        width:14%;
    }
    table.table thead th[group_id]{
        width:18%;
    }

    table.table th.group,
    table.table th.action,
    table.table th.controller
    {
        cursor:pointer;
    }
</style>
<?php $this->end() ?>
<?php $this->append('script') ?>
<script>
    $(function () {
        setHeader('th.group', 'group_id');
        setHeader('th.controller', 'controller');
        setHeader('th.action', 'action');
    });

    function setHeader(header, attr) {
        $(header).on({
            mouseover: function () {
                target = getTarget(this);
                target.addClass('bg-info');
            },
            mouseout: function () {
                target = getTarget(this);
                target.removeClass('bg-info');
            },
            click: function () {
                target = getTarget(this);
                first_value = target.filter('td.data:first').find('input[type=checkbox]').prop('checked');
                target.filter('td.data').find('input[type=checkbox]').prop('checked', !first_value);
            }
        });

        function getTarget(obj) {
            value = $(obj).attr(attr);
            return $('table.table td[' + attr + '=' + value + '],table.table th[' + attr + '=' + value + ']');
        }
    }
</script>
<?php $this->end() ?>
