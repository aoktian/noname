<?php
use I\DB;
use M\Middle_Public;

class Controller extends Middle_Public {
    public function index() {
        $this->view->html('pro-list', [
            'pros' => DB::write()->query("select * from pros"),
        ]);
    }

    public function store() {
        $id  = getgpc('id');
        $row = getgpc('row');

        $db                = DB::write();
        $now               = date('Y-m-d H:i:s');
        $row['updated_at'] = $now;
        if ($id) {
            $db->update('pros')->cols($row)->where('id=' . $id)->query();
        } else {
            $row['created_at'] = $now;
            $id                = $db->insert('pros')->cols($row)->query();
        }

        return $this->view->redirect('/pro/index');
    }

    public function destroy($id) {
        $db = DB::write();

        $row = $db->row('select count(*) as num from tasks where pro=' . $id);
        if ($row->num > 0) {
            $this->view->error('本项目下还有任务');
        }

        $row = $db->row('select count(*) as num from tags where pro=' . $id);
        if ($row->num > 0) {
            $this->view->error('本项目下还有版本');
        }

        $db->query("DELETE FROM `pros` WHERE id=$id");

        return $this->view->redirect('/pro/index');
    }
}
