<?php
if( isset($url)){
    echo $this->Html->link( $label , $url , ['class'=>"btn btn-sm btn-outline-{$color} py-0",'role'=>$action]);
}else{
    echo "<a class='btn btn-sm btn-outline-dark pt-0 pb-0 disabled'>{$label}</a>";
}