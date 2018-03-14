<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class EmailComponent extends Component {

    public function send($options) {
        $emailObj = new \Cake\Network\Email\Email($template);
        $emailObj
                ->viewVars(['data' => $data])
                ->to($data[Defines::REPAIR_DATA_EMAIL])
                ->send();
    }

}
