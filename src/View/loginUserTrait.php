<?php

namespace App\View;

trait loginUserTrait {
    public function getLoginUser($key = null, $default = null) {
        if (isset($key)) {
            $key = 'Auth.User.' . $key;
        } else {
            $key = 'Auth.User';
        }

        $result = $this->request->session()->read($key);

        if ($result === null) {
            return $default;
        }

        return $result;
    }
}
