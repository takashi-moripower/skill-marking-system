<?php

use App\Defines\Defines;

$condition_id = $this->request->getData('condition_id');

echo $this->Element('engineers/index_condition');
echo $this->Element('engineers/index');
echo $this->Element('paginator');
?>

