<?php
use I\DB;
use I\View;
use M\Middle_Public;

class Controller extends Middle_Public {
    public function edit($id) {
        return $this->view->html('feedback-edit', [
            'feedback' => DB::write()->row("select * from feedbacks where id = $id"),
        ]);
    }

    public function store() {
        $id  = getgpc('id');
        $row = getgpc('row');

        $row['author']  = $this->authed->id;
        $row['changer'] = $this->authed->id;

        $now               = date('Y-m-d H:i:s');
        $row['updated_at'] = $now;

        if (isset($row['message'])) {
            $row['message'] = removehost($row['message']);
        }

        $db = DB::write();
        if ($id) {
            $this->addlog($id, $row);
            $db->update('feedbacks')->cols($row)->where('id=' . $id)->query();
        } else {
            $row['created_at'] = $now;
            $id                = $db->insert('feedbacks')->cols($row)->query();
        }

        return $this->view->redirect('/task/show/' . $row['pid'] . '#feedback.' . $id);
    }

    private function addlog($id, $update) {
        $old     = DB::find('feedbacks', $id);
        $monitor = ['message'];
        $changed = array();
        foreach ($monitor as $col) {
            if (isset($update[$col]) && $update[$col] != $old->$col) {
                $changed[$col] = $old->$col;
            }
        }
        if (empty($changed)) {
            return;
        }

        if (isset($changed['author'])) {
            $row           = DB::find('users', $changed[$col]);
            $changed[$col] = $row->name;
        }

        $changed['pid']        = $old->id;
        $changed['changer']    = $this->authed->name;
        $changed['created_at'] = $update['updated_at'];

        DB::write()->insert('feedbacklogs')->cols($changed)->query();

    }

    public function show($id) {
        $feedback = DB::find('feedbacks', $id);
        $task     = DB::find('tasks', $feedback->pid);

        return $this->view->html('feedback-show', [
            'task'     => $task,
            'feedback' => $feedback,
            'logs'     => DB::write()->query("select * from feedbacklogs where pid=$id"),
            'users'    => DB::keyBy("select id, name, department from users"),
        ]);
    }

    public function content($id) {
        echo DB::find('feedbacks', $id)->message;
    }

}
