<?php
use C\Config_Wt;
use I\DB;
use I\Table;
use M\Middle_Public;

class Controller extends Middle_Public {
    public function index() {
        $db          = DB::write();
        $users       = $db->query('select id, email, name, department from users order by department');
        $departments = $db->query('select * from titles where caty = ' . Config_Wt::department);

        $this->view->html('users', [
            'users'       => keyBy($users),
            'departments' => keyBy($departments),
        ]);
    }

    public function delform($id) {
        $db          = DB::write();
        $departments = $db->query('select * from titles where caty = ' . Config_Wt::department);
        $this->view->dialog('user-del', [
            'user'        => $db->row("select * from users where id = $id"),
            'departments' => keyBy($departments),
        ]);
    }

    public function del() {
        $id   = getgpc('id', 0);
        $toid = getgpc('toid', 0);

        if ($toid > 0 && $id != $toid) {
            $db = DB::write();
            $db->query("DELETE FROM `users` WHERE id=$id");

            $db->update('tasks')->cols(array('leader' => $toid))->where('leader=' . $id)->query();
            $db->update('tasks')->cols(array('author' => $toid))->where('author=' . $id)->query();
            $db->update('tasks')->cols(array('changer' => $toid))->where('changer=' . $id)->query();
            $db->update('tasks')->cols(array('tester' => $toid))->where('tester=' . $id)->query();

            $db->update('feedbacks')->cols(array('author' => $toid))->where('author=' . $id)->query();
            $db->update('feedbacks')->cols(array('changer' => $toid))->where('changer=' . $id)->query();
        }

        $this->view->redirect('/user/index');
    }

    public function add() {
        $id    = getgpc('id');
        $udata = getgpc('udata');
        if (!$udata) {
            if (!$id) {
                $user = (object) [
                    'id'         => 0,
                    'name'       => '',
                    'email'      => '',
                    'department' => 0,
                ];
            } else {
                $user = Table::singleton('users')->find($id);
            }

            $db          = DB::write();
            $departments = $db->query('select * from titles where caty = ' . Config_Wt::department);

            $this->view->addData([
                'user'        => $user,
                'departments' => $departments,
            ]);
            return $this->view->html('user-add');
        }

        if ($id) {
            $user = Table::singleton('users')->find($id);
            if ($udata['password']) {
                $udata['password'] = password_hash($udata['password'], PASSWORD_DEFAULT);
            } else {
                unset($udata['password']);
            }

            //更新部门的时候
            if ($user->department != $udata['department']) {
                $db = DB::write();
                $db->query('update tasks set department=' . $udata['department'] . ' where leader=' . $user->id);
            }
            Table::singleton('users')->update($id, $udata);
        } else {
            $udata['password'] = password_hash($udata['password'], PASSWORD_DEFAULT);
            Table::singleton('users')->insert($udata);
        }

        $this->view->redirect('/user/index');
    }
}
