<div class="row justify-content-center mt-3">
    <div class="col-12 col-sm-10 col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="card-title">login</h2>
            </div>
            <div class="card-body">
                <?= $this->Form->create() ?>

                <div class="form-group">
                    <?= $this->Form->input('email', ['class' => 'form-control', 'placeHolder' => 'メールアドレスを入力してください']) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->input('password', ['class' => 'form-control', 'type' => 'password', 'placeHolder' => 'パスワードを入力してください']) ?>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">送信</button>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
