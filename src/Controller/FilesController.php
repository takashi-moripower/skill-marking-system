<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Files Controller
 *
 * @property \App\Model\Table\FilesTable $Files
 *
 * @method \App\Model\Entity\File[] paginate($object = null, array $settings = [])
 */
class FilesController extends AppController {

    public function load($id, $filename) {
        $this->autoRender = false;
        $file = $this->Files->get($id);
        $img = file_get_contents($file->tmp_name);

        $reg = "/(.*)(?:\.([^.]+$))/";
        $matches = [];
        $result = preg_match($reg, $file->name, $matches);


        if ($result && isset($matches[2])) {
            $this->response->type($matches[2]);
        }
        $this->response->body($img);
    }

    public function index($work_id) {
        $files = $this->Files->find()
                ->where(['work_id' => $work_id]);

        $this->set(['files' => $files]);
    }

}
