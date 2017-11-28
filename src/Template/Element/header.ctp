<?php

use App\Defines\Defines;
$loginUser = $this->request->session()->read('Auth.User');
?>
<header>
    <div class="bg-primary">
        <div class="container">
            <div class="row">
                <div class="col text-white clearfix">
                    <a href="<?= $this->Url->build("/") ?>" class="text-white float-left">
                        <h1><?= Defines::TITLE ?></h1>
                    </a>
                    <?php if ($loginUser): ?>
                        <div class="dropdown float-right mt-2">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= $loginUser->name ?>(<?= $loginUser->Groups['name'] ?>)
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?= $this->Url->Build(['controller' => 'Users', 'action' => 'logout']) ?>"><i class="fa fa-sign-out"></i>logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="dropdown float-right mt-2">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                GUEST
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?= $this->Url->Build(['controller' => 'Users', 'action' => 'login']) ?>"><i class="fa fa-sign-in"></i>login</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>
