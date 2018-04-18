<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use Cake\Utility\Hash;

class CommentsController extends AppController {

    public function add() {
        $comment = $this->Comments->newEntity($this->request->data);

        if ($this->Comments->save($comment)) {
            $this->Flash->success(__('コメントは正常に保存されました'));
        } else {
            $this->Flash->error(__('コメントの保存に失敗'));
        };

        $this->redirect(['controller' => 'works', 'action' => 'mark', $comment->work_id]);
    }

    public function edit($id) {
        $action = Hash::get($this->request->data, 'action', 'edit');
        $this->request->data = Hash::remove($this->request->data, 'action');

        $comment = $this->Comments->get($id);

        if ($action == 'delete') {
            if ($this->Comments->delete($comment)) {
                $this->Flash->success(__('コメントは正常に削除されました'));
            } else {
                $this->Flash->error(__('コメントの削除に失敗'));
            }
            return $this->redirect(['controller' => 'works', 'action' => 'mark', $comment->work_id]);
        }

        $this->Comments->patchEntity($comment, $this->request->data);
        if ($this->Comments->save($comment)) {
            $this->Flash->success(__('コメントは正常に保存されました'));
        } else {
            $this->Flash->error(__('コメントの保存に失敗'));
        };
        return $this->redirect(['controller' => 'works', 'action' => 'mark', $comment->work_id]);
    }
}
