<?php
$flash = $this->Flash->render();
if ($flash != null):
    ?>
    <div class="row justify-content-center">
        <div class="col-6 text-center mt-3 mb-3">
            <?= $flash ?>
        </div>
    </div>
    <?php
endif;
?>
