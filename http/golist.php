<?php
use I\DB;
use I\Table;
use M\Middle_Public;

class Controller extends Middle_Public {
    public function index() {
        $db = DB::write();

        $this->view->html('golist-index', [
            'mods'   => $db->query('select * from golist_mod order by r'),
            'ones'   => keyBy2($db->query('select * from golist_one order by r'), 'modid', 'id'),
            'onests' => keyBy2($db->query('select * from golist_ones'), 'oid', 'tid'),
            'tasks'  => keyBy($db->query('select * from golist_task')),
            'gs'     => keyBy($db->query('select * from golist_taskg')),
            'gst'    => keyBy2($db->query('select * from golist_taskgs order by r'), 'gid', 'tid'),
        ]);
    }

    public function task() {
        $db = DB::write();
        $this->view->html('golist-task', [
            'list' => $db->query('select * from golist_task order by id'),
        ]);
    }

    public function task_ae() {
        $id    = getgpc('id');
        $udata = getgpc('udata');

        $row = Table::singleton('golist_task')->findone(['name' => $udata['name']]);
        if ($row) {
            return $this->view->assertAlert('名字已经存在');
        }

        if ($id) {
            Table::singleton('golist_task')->update($id, $udata);
        } else {
            Table::singleton('golist_task')->insert($udata);
        }
        $this->view->redirect('/golist/task');
    }

    public function task_del($id) {
        $id = getgpc('id');

        Table::singleton('golist_task')->del($id);
        Table::singleton('golist_taskgs')->delwhere(['tid' => $id]);
        Table::singleton('golist')->delwhere(['tid' => $id]);

        $this->view->redirect('/golist/index');
    }

    public function group() {
        $db       = DB::write();
        $children = $db->query('select * from golist_taskgs order by r');
        $gs       = keyBy2($children, 'gid', 'tid');
        $tasks    = $db->query('select * from golist_task');
        $this->view->html('golist-group', [
            'list'  => $db->query('select * from golist_taskg'),
            'gs'    => $gs,
            'tasks' => keyBy($tasks),
        ]);
    }

    public function groupadd() {
        $db = DB::write();

        $udata = getgpc('udata');
        Table::singleton('golist_taskg')->insert($udata);

        $this->view->redirect('/golist/group');
    }

    private function getids($tname, $sqladd) {
        $list = Table::singleton($tname)->wherelist($sqladd);
        $ids  = array();
        foreach ($list as $one) {
            $ids[] = $one->id;
        }
        return implode(',', $ids);
    }

    public function groupdel($id) {
        $id = getgpc('id');
        DB::beginTrans();
        $db = DB::write();

        $mod_ids = $this->getids('golist_mod', "where gid=$id");
        if ($mod_ids) {
            $db->query("delete from golist_mod where id in($mod_ids)");

            $one_ids = $this->getids('golist_one', "where modid in($mod_ids)");
            if ($one_ids) {
                $db->query("delete from golist_one where id in($one_ids)");
                $db->query("delete from golist_ones where oid in($one_ids)");
            }
        }

        Table::singleton('golist_taskg')->del($id);
        Table::singleton('golist_taskgs')->delwhere(['gid' => $id]);

        DB::commitTrans();
        $this->view->redirect('/golist/index');
    }

    public function groupdelt($id) {
        $gid = getgpc('gid');
        $tid = getgpc('tid');

        DB::beginTrans();
        Table::singleton('golist_taskgs')->delwhere(
            ['gid' => $gid, 'tid' => $tid]
        );

        $db  = DB::write();
        $sql = 'delete from golist where tid = ' . $tid . ' and mod in (select id from golist_mod where gid = ' . $gid . ')';
        $db->query($sql);

        DB::commitTrans();

        $this->view->redirect('/golist/index');
    }

    public function groupslt($id) {
        $db = DB::write();
        $gs = $db->query('select * from golist_taskgs where gid = ' . $id);

        $gs = keyBy($gs, 'tid');

        $this->view->dialog('golist-groupslt', [
            'list'  => $db->query('select * from golist_task order by id'),
            'group' => $db->row('select * from golist_taskg where id = ' . $id),
            'gs'    => $gs,
            'id'    => $id,
        ]);
    }

    public function groupslted() {
        $id = getgpc('id');

        Table::singleton('golist_taskg')->update($id, ['name' => getgpc('name')]);

        $db = DB::write();

        DB::beginTrans();
        $row  = $db->row('select max(r) as r from golist_taskgs where gid = ' . $id);
        $maxr = $row->r ? $row->r : 0;

        $gs = $db->query('select * from golist_taskgs where gid = ' . $id);
        $gs = keyBy($gs, 'tid');

        $ids = getgpc('ids');

        $delids  = [];
        $deltids = [];
        foreach ($gs as $tid => $value) {
            if (isset($ids[$tid])) {
                continue;
            }
            $delids[]  = $value->id;
            $deltids[] = $tid;
        }

        if ($delids) {
            $s_delids = implode(',', $delids);
            $db->query('delete from golist_taskgs where id in (' . $s_delids . ')');

            $sql = sprintf('delete from golist_ones where tid in (%s) and oid in(select id from golist_one where modid in(select id from golist_mod where gid =%s))',
                implode(',', $deltids),
                $id
            );
            $db->query($sql);
        }

        $mods = $db->query("select id from golist_mod where gid = $id");

        $ones = array();
        if ($mods) {
            $m_ids = array();
            foreach ($mods as $mod) {
                $m_ids[] = $mod->id;
            }
            $sql  = sprintf('select * from golist_one where modid in(%s)', implode(',', $m_ids));
            $ones = $db->query($sql);
        }

        $onevalues = array();
        $values    = [];
        foreach ($ids as $value) {
            if (isset($list[$value])) {
                continue;
            }

            $maxr++;
            $values[] = sprintf('(%s, %s, %s)', $id, $value, $maxr);

            foreach ($ones as $one) {
                $onevalues[] = sprintf('(%s, %s)', $one->id, $value);
            }
        }

        if ($onevalues) {
            Table::singleton('golist_ones')->insertintovalues('oid, tid', $onevalues);
        }

        Table::singleton('golist_taskgs')->insertintovalues(
            'gid, tid, r',
            $values
        );

        DB::commitTrans();

        $this->view->redirect('/golist/group');
    }

    public function group_r() {
        $id     = getgpc('id');
        $updown = getgpc('updown');

        $db = DB::write();

        $a   = $db->row("select * from golist_taskgs where id = $id");
        $r   = $a->r;
        $gid = $a->gid;
        if ($updown == 'up') {
            $b = $db->row("select * from golist_taskgs where gid=$gid and r < $r order by r desc limit 1");
        } else {
            $b = $db->row('select * from golist_taskgs where gid=$gid and r > $r order by r limit 1');
        }
        if (!$b) {
            $this->view->ajax();
        }

        Table::singleton('golist_taskgs')->update($a->id, ['r' => $b->r]);
        Table::singleton('golist_taskgs')->update($b->id, ['r' => $a->r]);

        $this->view->ajax();
    }

    public function mod() {
        $db    = DB::write();
        $g     = $db->query('select * from golist_taskg');
        $gs    = $db->query('select * from golist_taskgs order by r');
        $tasks = $db->query('select * from golist_task');

        $this->view->html('golist-mod', [
            'list'  => $db->query('select * from golist_mod order by r'),
            'g'     => keyBy($g),
            'gs'    => keyBy2($gs, 'gid', 'tid'),
            'tasks' => keyBy($tasks),
        ]);
    }

    public function mod_ae() {
        $id    = getgpc('id');
        $udata = getgpc('udata');

        if (!$udata['gid']) {
            return $this->view->assertAlert('没有选择任务组，如果没有，先去《组组组》添加');
        }

        if ($id) {
            $this->mod_newgid($id, $udata);
            Table::singleton('golist_mod')->update($id, $udata);
        } else {
            $row = Table::singleton('golist_mod')->row('max(r) as r');

            $maxr       = $row->r ? $row->r : 0;
            $udata['r'] = $maxr + 1;
            Table::singleton('golist_mod')->insert($udata);
        }
        $this->view->redirect('/golist/mod');
    }

    private function mod_newgid($id, $udata) {
        $mod = Table::singleton('golist_mod')->find($id);
        if ($mod->gid == $udata['gid']) {
            return;
        }
        $one_ids = [];
        $ones    = Table::singleton('golist_one')->select(['modid' => $id]);
        if (!$ones) {
            return;
        }

        foreach ($ones as $one) {
            $one_ids[] = $one->id;
        }
        $one_ids_s = implode(',', $one_ids);
        $old_tids  = keyBy(Table::singleton('golist_taskgs')->select(['gid' => $mod->gid]), 'tid');
        $new_tids  = keyBy(Table::singleton('golist_taskgs')->select(['gid' => $udata['gid']]), 'tid');
        foreach ($old_tids as $key => $value) {
            if (isset($new_tids[$key])) {
                continue;
            }

            $db->query("delete from golist_ones where oid in ($one_ids_s) and tid = $key");
        }
        $values = array();
        foreach ($new_tids as $key => $value) {
            if (isset($old_tids[$key])) {
                continue;
            }
            foreach ($ones as $one) {
                $values[] = sprintf('(%s, %s)', $one->id, $key);
            }
        }
        Table::singleton('golist_ones')->insertintovalues('oid, tid', $values);
    }

    public function mod_modify($id) {
        $db = DB::write();
        $g  = $db->query('select * from golist_taskg');

        $this->view->dialog('golist-mod-modify', [
            'one' => $db->row('select * from golist_mod where id =' . $id),
            'g'   => keyBy($g),
        ]);
    }

    public function mod_r() {
        $id     = getgpc('id');
        $updown = getgpc('updown');

        $db = DB::write();

        $a = $db->row('select * from golist_mod where id = ' . $id);
        $r = $a->r;
        if ($updown == 'up') {
            $b = $db->row("select * from golist_mod where r <$r order by r desc limit 1");
        } else {
            $b = $db->row("select * from golist_mod where r >$r order by r limit 1");
        }
        if (!$b) {
            $this->view->redirect('/golist/mod');
        }

        Table::singleton('golist_mod')->update($a->id, ['r' => $b->r]);
        Table::singleton('golist_mod')->update($b->id, ['r' => $a->r]);
        $this->view->ajax();
    }

    public function mod_del($id) {
        DB::beginTrans();

        $db = DB::write();
        Table::singleton('golist_mod')->del($id);

        $db->query('delete from golist_ones where oid in (select id from golist_one where modid=' . $id . ')');

        Table::singleton('golist_one')->delwhere(['modid' => $id]);

        DB::commitTrans();

        $this->view->redirect('/golist/mod');
    }

    private function one_newmod($id, &$udata) {
        $one = Table::singleton('golist_one')->find($id);
        if ($one->modid == $udata['modid']) {
            unset($udata['modid']);
            return;
        }

        $old_mod = Table::singleton('golist_mod')->find($one->modid);
        $new_mod = Table::singleton('golist_mod')->find($udata['modid']);

        if ($old_mod->gid == $new_mod->gid) {
            return;
        }

        $old_tids = keyBy(Table::singleton('golist_taskgs')->select(['gid' => $old_mod->gid]), 'tid');
        $new_tids = keyBy(Table::singleton('golist_taskgs')->select(['gid' => $new_mod->gid]), 'tid');
        foreach ($old_tids as $key => $value) {
            if (isset($new_tids[$key])) {
                continue;
            }

            $db->query("delete from golist_ones where oid = $id and tid = $key");
        }

        $values = array();
        foreach ($new_tids as $key => $value) {
            if (isset($old_tids[$key])) {
                continue;
            }

            $values[] = sprintf('(%s, %s)', $one->id, $key);
        }
        Table::singleton('golist_ones')->insertintovalues('oid, tid', $values);
    }

    public function one_status($id) {
        $udata = getgpc('udata');
        Table::singleton('golist_ones')->update($id, $udata);
        $this->view->ajax();
    }

    public function one_r() {
        $id     = getgpc('id');
        $updown = getgpc('updown');

        $db = DB::write();

        $a     = $db->row("select * from golist_one where id = $id");
        $r     = $a->r;
        $modid = $a->modid;
        if ($updown == 'up') {
            $b = $db->row("select * from golist_one where modid = $modid and r <$r order by r desc limit 1");
        } else {
            $b = $db->row("select * from golist_one where modid = $modid and r >$r order by r limit 1");
        }
        if (!$b) {
            $this->view->redirect('/golist/index');
        }

        Table::singleton('golist_one')->update($a->id, ['r' => $b->r]);
        Table::singleton('golist_one')->update($b->id, ['r' => $a->r]);

        $this->view->ajax();
    }

    public function one_add() {
        $id = getgpc('id');

        DB::beginTrans();

        $udata = getgpc('udata');

        if (!$udata) {
            if ($id) {
                $one = Table::singleton('golist_one')->find($id);
            } else {
                $modid = getgpc('modid');
                $one   = (object) [
                    'id'    => 0,
                    'name'  => '',
                    'modid' => $modid,
                ];
            }
            return $this->view->dialog('golist-one-add', [
                'one'  => $one,
                'mods' => Table::singleton('golist_mod')->all(),
            ]);
        }

        $modid = $udata['modid'];
        if ($id) {
            $this->one_newmod($id, $udata);
            Table::singleton('golist_one')->update($id, $udata);
        } else {
            $db         = DB::write();
            $row        = $db->row('select max(r) as r from golist_one where modid = ' . $udata['modid']);
            $maxr       = $row->r ? $row->r : 0;
            $udata['r'] = $maxr + 1;
            $oid        = Table::singleton('golist_one')->insert($udata);

            $mod = Table::singleton('golist_mod')->find($udata['modid']);

            $db    = DB::write();
            $tasks = $db->query('select * from golist_taskgs where gid=' . $mod->gid . ' order by r');

            $values = [];
            foreach ($tasks as $task) {
                $values[] = sprintf('(%s, %s)', $oid, $task->tid);
            }
            Table::singleton('golist_ones')->insertintovalues('oid, tid', $values);
        }

        DB::commitTrans();
        $this->view->redirect('/golist/index#mod-' . $modid);
    }

    public function one_del($id) {
        $one = Table::singleton('golist_one')->find($id);
        DB::beginTrans();
        Table::singleton('golist_one')->del($id);
        Table::singleton('golist_ones')->delwhere(['oid' => $id]);
        DB::commitTrans();
        $this->view->redirect('/golist/index#mod-' . $one->modid);
    }

}
