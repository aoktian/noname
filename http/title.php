<?php
use I\DB;
use M\Middle_Public;

class Controller extends Middle_Public {
    public function index() {
        $this->view->addData([
            'currmenu' => 'pro',
        ]);

        return $this->view->html('title-list', [
            'titles' => DB::write()->query('select * from titles order by id'),
        ]);
    }

    public function store() {
        $id = getgpc('id');
        $db = DB::write();

        $row = getgpc('row');

        $name = trim($row['name']);
        if (!$name) {
            return $this->view->redirect('/title/index');
        }

        $db = DB::write();
        if ($id) {
            $title = $db->row('select * from titles where id = ' . $id);
            if ($title->locked) {
                unset($row['name']);
            }
            $db->update('titles')->cols($row)->where('id=' . $id)->query();
        } else {
            if (!$row['caty']) {
                return $this->view->redirect('/title/index');
            }

            $id = $db->insert('titles')->cols($row)->query();
        }

        return $this->view->redirect('/title/index');
    }

    public function del($id) {
        $title = $db->row('select * from titles where id = ' . $id);

        if ($title->locked) {
            return $this->view->redirect('/title/index');
        }

        return $this->view->html('title-del', [
            'title'  => $title,
            'titles' => DB::keyBy('select * from titles order by id'),
        ]);
    }

    public function destroy() {
        $id    = getgpc('id');
        $title = $db->row('select * from titles where id = ' . $id);
        if ($title->locked || $title->id == $toid) {
            return $this->view->redirect('/title/index');
        }

        $toid    = getgpc('toid');
        $totitle = $db->row('select * from titles where id = ' . $toid);

        if ($title->caty != $totitle->caty) {
            return $this->view->redirect('/title/index');
        }

        if ($title->caty == Setting::get('worktime', 'caty')) {
            $db->update('tasks')->cols(['caty' => $toid])->where('caty=' . $id)->query();
        } elseif ($title->caty == Setting::get('worktime', 'department')) {
            $db->update('users')->cols(['department' => $toid])->where('department=' . $id)->query();
            $db->update('tasks')->cols(['department' => $toid])->where('department=' . $id)->query();
        } else {
            return $this->view->redirect('/title/index');
        }

        $db->query("DELETE FROM `titles` WHERE id=$id");

        return $this->view->redirect('/title/index');
    }
}
