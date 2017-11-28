<?php
use App\Defines\Defines;
?>

<div class="card">
    <div class="card-header border-bottom-0">
        <h2>一括追加</h2>
    </div>
    <div class="card-body p-0">
        <?= $this->Form->create(null, ['method' => 'post', 'enctype' => 'multipart/form-data']) ?>
        <table class="table table-bordered mb-0">
            <tbody>
                <tr>
                    <th class="">対象組織</th>
                    <td class="">
            <?= $this->Form->select('organization_id', $organizations) ?>
                    </td>
                </tr>
                <tr>
                    <th>CSVファイル</th>
                    <td>
            <?= $this->Form->file('file'); ?>
                    </td>
                </tr>
                <tr>
                    <th>ファイルエンコード</th>
                    <td>
                        <?= $this->Form->select('encode',Defines::ENCODING) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right">
                        <button class="btn btn-primary">実行</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <?= $this->Form->end(); ?>
    </div>
</div>
<div class="card mt-2">
    <div class="card-body">
        csvファイルを使って対象組織に複数の技術者を一括登録します<br/>
        書式は以下の通り
        <pre>

        ユーザー名1,Email1,パスワード1
        ユーザー名2,Email2,パスワード2
        </pre>
    </div>
</div>
