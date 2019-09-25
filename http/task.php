<?php
use C\Config_Wt;
use I\App;
use I\DB;
use I\Table;
use M\Middle_Public;

class Controller extends Middle_Public {
    public function index() {
        return $this->formatlist([]);
    }

    public function ido() {
        return $this->formatlist(['leader' => $this->authed->id]);
    }

    public function icommit() {
        return $this->formatlist(['author' => $this->authed->id]);
    }

    public function itest() {
        return $this->formatlist(['tester' => $this->authed->id]);
    }

    private function getSortedusers() {
        $users     = DB::keyBy("select id, name, department from users");
        $sortusers = array();
        foreach ($users as $k => $v) {
            $sortusers[$k] = iconv('UTF-8', 'GBK', $v->name);
        }
        asort($sortusers);
        $sortedusers = array();
        foreach ($sortusers as $k => $name) {
            $sortedusers[$k] = $users[$k];
        }
        return $sortedusers;
    }

    private function formatlist($searchargs) {
        $db  = DB::write();
        $ids = getgpc('ids');
        if ($ids) {
            $updates = array();
            foreach (getgpc('changeto') as $key => $value) {
                if ($value > 0) {
                    $updates[$key] = $value;
                }
            }

            if (isset($updates['tag'])) {
                $tag            = $db->row('select * from tags where id = ' . $updates['tag']);
                $updates['pro'] = $tag->pro;
            }
            if (isset($updates['leader'])) {
                $leader                = $db->row('select * from users where id = ' . $updates['leader']);
                $updates['department'] = $leader->department;
            }

            if ($updates) {
                $updates['updated_at'] = date('Y-m-d H:i:s');
                $db->update('tasks')->cols($updates)->where('ID in(' . implode(',', $ids) . ')')->query();
            }
        }

        $options = array();
        $search  = getgpc('search');
        if (!$search) {
            $search = array();
        }

        $search = array_merge($search, $searchargs);

        $where = array();
        foreach ($search as $key => $value) {
            if ($value > 0) {
                $options[$key] = $value;
                $where[]       = $key . '="' . addslashes($value) . '"';
            }
        }

        $title = getgpc('title');
        if ($title) {
            $options['title'] = $title;
            $where[]          = 'title like "%' . addslashes($title) . '%"';
        }

        $ismain = getgpc('ismain', 0);
        if ($ismain) {
            $where[] = 'related=0';
        }

        $sqlcount = 'select count(*) as num from tasks';
        $sql      = 'select * from tasks';
        if (count($where)) {
            $wheresql = implode(' and ', $where);
            $sqlcount .= ' where ' . $wheresql;
            $sql .= ' where ' . $wheresql;
        }

        $count_row = $db->row($sqlcount);
        $totalnum  = $count_row->num;
        $curpage   = getgpc('page', 1);
        $perpage   = 20;
        $offset    = page_get_start($curpage, $perpage, $totalnum);

        $orderby = getgpc('orderby');
        if ($orderby) {
            $sql .= ' order by updated_at desc';
        } else {
            $sql .= ' order by status';
            $sql .= ', priority desc';
            $sql .= ', tag';
            $sql .= ', updated_at desc';
            $orderby = '';
        }

        $sql .= " limit $perpage offset $offset";
        $tasks = $db->query($sql);

        $tpl = 'task-list';
        if ($this->view->isajax()) {
            $tpl = 'task-list-content';
        }

        $this->view->addData([
            'tasks'       => $tasks,
            'pros'        => DB::keyBy("select * from pros"),
            'users'       => $this->getSortedusers(),
            'tags'        => DB::keyBy("select id, name, pro from tags order by id desc"),
            'status'      => App::singleton()->getconfig('worktime', 'status'),
            'prioritys'   => App::singleton()->getconfig('worktime', 'priority'),
            'catys'       => DB::keyBy("select * from titles where caty = " . App::singleton()->getconfig('worktime', 'caty')),
            'departments' => DB::keyBy("select * from titles where caty = " . App::singleton()->getconfig('worktime', 'department')),
            'options'     => $options,
            'orderby'     => $orderby,
            'ismain'      => $ismain,
            'totalnum'    => $totalnum,
            'curpage'     => $curpage,
            'perpage'     => $perpage,
        ]);

        return $this->view->html($tpl);
    }

    public function create($id = 0, $related_id = 0) {
        $db = DB::write();
        if ($id) {
            $task       = $db->row("SELECT * FROM `tasks` WHERE id='$id'");
            $related_id = $task->related;
        } else {
            $task = NULL;
        }

        if ($related_id) {
            $related = $db->row("SELECT * FROM `tasks` WHERE id='$related_id'");
        } else {
            $related = NULL;
        }

        return $this->view->html('task-commit', [
            'task'        => $task,
            'related'     => $related,
            'pros'        => DB::keyBy("select * from pros"),
            'users'       => $this->getSortedusers(),
            'tags'        => DB::keyBy("select id, name, pro from tags order by id desc"),
            'status'      => App::singleton()->getconfig('worktime', 'status'),
            'catys'       => DB::keyBy("select * from titles where caty = " . App::singleton()->getconfig('worktime', 'caty')),
            'departments' => DB::keyBy("select * from titles where caty = " . App::singleton()->getconfig('worktime', 'department')),
        ]);
    }

    public function store() {
        $id  = getgpc('id');
        $row = getgpc('row');

        $me = $this->authed;

        $db = DB::write();
        DB::beginTrans();

        $row['deadline'] = strtotime($row['deadline']);
        $row['changer']  = $me->id;

        if ($id) {
            $task = DB::find('tasks', $id);
            foreach ($row as $k => $v) {
                if ($task->$k == $v) {
                    unset($row[$k]);
                }
            }

            if (empty($row)) {
                return $this->view->assertAlert('没做任何操作');
            }
        } else {
            $row['author'] = $me->id;
            $row['status'] = 12;
        }

        $now               = date('Y-m-d H:i:s');
        $row['updated_at'] = $now;

        if (isset($row['content'])) {
            $row['content'] = removehost($row['content']);
        }

        $this->onChange($row);

        if ($id) {
            $this->addlog($task, $row);
            $db->update('tasks')->cols($row)->where('id=' . $id)->query();
        } else {
            $row['created_at'] = $now;
            $id                = $db->insert('tasks')->cols($row)->query();
        }

        DB::commitTrans();
        if ($this->view->isajax()) {
            $this->view->alert('修改成功');
        } else {
            $this->view->redirect('/task/show/' . $id);
        }
    }

    private function addlog($old, $update) {
        $monitor = array(
            'title', 'content', 'caty',
            'priority', 'department', 'status',
            'tag', 'pro', 'deadline',
            'changer', 'leader', 'tester',
        );
        $changed = array();
        foreach ($monitor as $col) {
            if (isset($update[$col]) && $update[$col] != $old->$col) {
                $changed[$col] = $old->$col;
            }
        }

        if (empty($changed)) {
            return;
        }

        if (isset($changed['caty'])) {
            $row             = DB::find('titles', $changed['caty']);
            $changed['caty'] = $row->name;
        }
        if (isset($changed['department'])) {
            $row                   = DB::find('titles', $changed['department']);
            $changed['department'] = $row->name;
        }

        if (isset($changed['priority'])) {
            $changed['priority'] = App::singleton()->getconfig('worktime', 'priority')[$changed['priority']];
        }
        if (isset($changed['status'])) {
            $changed['status'] = App::singleton()->getconfig('worktime', 'status')[$changed['status']];
        }

        if (isset($changed['tag'])) {
            $row            = DB::find('tags', $changed['tag']);
            $changed['tag'] = $row->name;
        }

        if (isset($changed['pro'])) {
            $row            = DB::find('pros', $changed['pro']);
            $changed['pro'] = $row->name;
        }

        foreach (['author', 'leader', 'tester', 'changer'] as $col) {
            if (isset($changed[$col])) {
                $row           = DB::find('users', $changed[$col]);
                $changed[$col] = $row->name;
            }
        }

        $changed['pid']        = $old->id;
        $changed['changer']    = $this->authed->name;
        $changed['created_at'] = $update['updated_at'];

        DB::write()->insert('tasklogs')->cols($changed)->query();
    }

    private function onChange(&$row) {
        if (isset($row['tag'])) {
            $tag        = DB::find('tags', $row['tag']);
            $row['pro'] = $tag->pro;
        }

        if (isset($row['leader'])) {
            $leader            = DB::find('users', $row['leader']);
            $row['department'] = $leader->department;
        }
    }

    public function show($id) {
        $db   = DB::write();
        $task = $db->row('select * from tasks where id=' . $id);

        $relatedsql = sprintf('select * from tasks where related = %s order by r limit 100', $task->related ? $task->related : $id);

        $parent_task = NULL;
        if ($task->related) {
            $parent_task = $db->row('select * from tasks where id=' . $task->related);
            if (!$parent_task) {
                $task->related = 0;
                Table::singleton('tasks')->update($task->id, ['related' => 0]);
            }
        }

        $this->view->addData([
            'task'        => $task,
            'parent_task' => $parent_task,
            'tasks'       => $db->query($relatedsql),
            'feedbacks'   => $db->query("select * from feedbacks where pid=$id"),
            'users'       => $this->getSortedusers(),
            'prioritys'   => App::singleton()->getconfig('worktime', 'priority'),
            'tags'        => DB::keyBy('select id, name, pro from tags'),
            'pros'        => DB::keyBy('select * from pros'),
            'catys'       => DB::keyBy("select * from titles where caty = " . App::singleton()->getconfig('worktime', 'caty')),
            'departments' => DB::keyBy("select * from titles where caty = " . App::singleton()->getconfig('worktime', 'department')),
        ]);

        return $this->view->html('task-show', [
            'logs' => $db->query("select * from tasklogs where pid=$id"),
        ]);
    }

    public function related() {
        $db = DB::write();

        $related = getgpc('related', 0); //子任务
        $id      = getgpc('id', 0); //主任务

        if (!$related) {
            $this->view->assertAlert('输入错误');
        }
        if ($related == $id) {
            $this->view->assertAlert('自己不能关联自己');
        }

        $udata = [
            'related' => $id,
        ];
        $db->update('tasks')->cols($udata)->where('id=' . $related)->query();

        $this->view->redirect('/task/show/' . $id);
    }

    public function content($id) {
        echo (DB::find('tasks', $id)->content);
        exit();
    }

    public function upload() {
        $a = array('err' => 'do not recive file.');

        if (!isset($_FILES['file'])) {
            echo json_encode($a);exit();
        }

        $filedir = '/' . date('Ym');
        $homedir = Config_Wt::uploaddir;

        $uploaddir = $homedir . $filedir;
        if (!is_dir($uploaddir)) {
            @mkdir($uploaddir);
        }

        $file = $_FILES['file'];

        if (0 == $file['size']) {
            echo json_encode($a);exit();
        }

        $filename   = time() . '_' . rand(10000, 99999);
        $uploadfile = $uploaddir . '/' . $filename;
        if (!file_exists($uploadfile)) {
            @move_uploaded_file($file['tmp_name'], $uploadfile);
        }

        $a['path'] = Config_Wt::imgpath . $filedir . '/' . $filename;

        echo json_encode($a);exit();
    }

    public function diff($table, $oldid, $newid, $islog = 0) {

        if ($islog) {
            $old = DB::find($table . 'logs', $oldid);
            $new = DB::find($table . 'logs', $newid);
        } else {
            $old = DB::find($table . 'logs', $oldid);
            $new = DB::find($table . 's', $newid);
        }

        if ('task' == $table) {
            $diff = new I\HtmlDiff($old->content, $new->content);
        } else {
            $diff = new I\HtmlDiff($old->message, $new->message);
        }
        $diff->build();
        // echo "<h2>Old html</h2>";
        // echo $diff->getOldHtml();
        // echo "<h2>New html</h2>";
        // echo $diff->getNewHtml();
        // echo "<h2>Compared html</h2>";
        // echo $diff->getDifference();

        echo $diff->getDifference();exit();
    }

    public function sql() {
        $content = getgpc('c');
        if (!$content) {
            return $this->view->html('sql');
        }

        $db   = DB::write();
        $list = $db->query($content);
        $this->view->addData([
            'tasks'       => $list,
            'pros'        => DB::keyBy("select * from pros"),
            'users'       => $this->getSortedusers(),
            'tags'        => DB::keyBy("select id, name, pro from tags order by id desc"),
            'status'      => App::singleton()->getconfig('worktime', 'status'),
            'prioritys'   => App::singleton()->getconfig('worktime', 'priority'),
            'catys'       => DB::keyBy("select * from titles where caty = " . App::singleton()->getconfig('worktime', 'caty')),
            'departments' => DB::keyBy("select * from titles where caty = " . App::singleton()->getconfig('worktime', 'department')),
        ]);

        return $this->view->html('sql-content');
    }

    public function rrr($id) {
        $db = DB::write();

        $updown = getgpc('updown');

        $a = $db->row("select * from tasks where id = $id");
        $r = $a->r;

        if ($r <= 0) {
            $this->view->assertAlert('本任务没有依赖，不需要顺序');
        }

        $related = $a->related;
        if ($related <= 0) {
            $this->view->assertAlert('不是子任务');
        }

        if ($updown == 'up') {
            $b = $db->row("select * from tasks where related=$related and r < $r order by r desc limit 1");
        } else {
            $b = $db->row("select * from tasks where related=$related and r > $r order by r limit 1");
        }
        if (!$b) {
            $this->view->ajax();
        }

        Table::singleton('tasks')->update($a->id, ['r' => $b->r]);
        Table::singleton('tasks')->update($b->id, ['r' => $a->r]);

        $this->view->ajax();
    }

    //去掉依赖
    public function no_r($id) {
        Table::singleton('tasks')->update($id, ['r' => 0]);
        $this->view->redirect('/task/show/' . $id);
    }

    //加入流程，直接放到最下面
    public function add_r($id) {
        $db   = DB::write();
        $row  = $db->row('select max(r) as r from tasks where related = ' . $id);
        $maxr = $row->r ? $row->r : 0;

        Table::singleton('tasks')->update($id, ['r' => $maxr + 1]);
        $this->view->redirect('/task/show/' . $id);
    }

}
