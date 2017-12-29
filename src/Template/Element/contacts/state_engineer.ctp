<?php
use App\Defines\Defines;

if( $contact->flags &  Defines::CONTACT_FLAG_ALLOW_BY_ENGINEER ){
    echo '承認済';
}else if($contact->flags &  Defines::CONTACT_FLAG_DENIED_BY_ENGINEER ){
    echo '否認済';
}else{
    echo '未設定';
}
?>
