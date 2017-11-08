<?php
$loginUserId = $this->getLoginUser('id');
?>



<div class="card">
    <div class="card-header">
        <h2>お知らせ</h2>

    </div>
    <div class="card-body">
        <ul>
            <li>このシステムは、クリエイターが作品を投稿し、業界の方がスキルを評価するものです。</li>
            <li>まずは新規投稿から、作品をアップしましょう</li>
        </ul>

        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <img src="<?= $this->Url->build('/img/engineers/new_work.png', 1) ?>" class="img-fluid"/>

                    </div>

                </div>

            </div>
            <div class="col-7">
                <dl>
                    <dt>題名</dt>
                    <dd>わかりやすい題名を入れてください</dd>
                    <dt>ジャンル</dt>
                    <dd>ジャンルは複数追加できます</dd>
                    <dt>解説</dt>
                    <dd>作品の説明、自己アピールなど自由に記載してください</dd>
                    <dt>添付ファイル</dt>
                    <dd>作品をアップロードしてください、複数ファイルの場合は、フォルダにまとめてzip圧縮して送るようにしてください。</dd>
                </dl>
            </div>
        </div>
        <ul>
            <li>評価してほしいスキル項目があれば、一覧から該当する作品を選んでスキル項を登録してください。その際　自己評価することもできます</li>
            <li>その後、学校の先生か業界の方が、あなたの作品を評価します</li>
            <li>自己評価と比較することで、自分のレベルを正しく知ることができます</li>
        </ul>
    </div>
</div>
