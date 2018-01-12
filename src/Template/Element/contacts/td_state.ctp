<?php
use App\Defines\Defines;
if( $state == Defines::CONTACT_STATE_ALLOW ){
    $class = "alert alert-success";
}elseif($state == Defines::CONTACT_STATE_DENY){
    $class = "alert alert-danger";
}else{
    $class = '';
}
?>
<td class="<?= $class ?>">
<?php
echo $labels[$state];
?>
</td>