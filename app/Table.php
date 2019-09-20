<?php
namespace I;
class Table {
    const ADMIN = "admin";
    const USERS = "users";

    const ADDRESS = "address";
    const PINDAN  = "pindan";

    const BONUS = "bonus";

    const STAT_BRANCH = "stat_branch";
    const STAT_CEO    = "stat_ceo";

    const TRADE          = "trade";
    const TRADE_ITEMS    = "trade_items";
    const DELIVERY       = "delivery";
    const DELIVERY_ITEMS = "delivery_items";

    const MONEYDETAIL = "moneydetail";

    const GOODS = "goods";

    private static $instances = array();
    public static function singleton($name) {
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new self($name);
        }
        return self::$instances[$name];
    }

    private $name = '';
    private $db   = NULL;
    public function __construct($name) {
        $this->name = $name;
        $this->db   = DB::write();
    }

    public function findone($conds) {
        $this->db->select('*')->from($this->name);
        foreach ($conds as $k => $v) {
            $this->db->where($k . '= :' . $k);
        }
        $one = $this->db->bindValues($conds)->row();
        return $one;
    }

    public function row($cols, $conds = NULL) {
        $sql = 'select ' . $cols . ' from ' . $this->name;
        if ($conds) {
            $sql .= ' where ' . $conds;
        }
        return $this->db->row($sql);
    }

    private $list = array();
    public function find($id) {
        $_k = $this->name . '_' . $id;
        if (!isset($this->list[$_k]) || !$this->list[$_k]) {
            $this->list[$_k] = DB::find($this->name, $id);
        }
        return $this->list[$_k];
    }

    public function findassert($id, $limituser) {
        $row = $this->find($id);
        if (!$row || $row->user_id != $limituser) {
            $view = View::singleton();
            return $view->assertToast('不合法');
        }
        return $row;
    }

    public function del($id) {
        $this->db->delete($this->name)->where('id=' . $id)->query();
    }

    public function delwhere($conds) {
        $this->db->delete($this->name);
        foreach ($conds as $k => $v) {
            $this->db->where($k . '= :' . $k);
        }
        $this->db->bindValues($conds);
        $this->db->query();
    }

    public function insertintovalues($a, $vs) {
        $sql = sprintf('insert into %s(%s) values%s', $this->name, $a, implode(',', $vs));
        $this->db->query($sql);
    }

    public function all() {
        $sql = 'select * from ' . $this->name;
        return $this->db->query($sql);
    }

    public function select($conds) {
        $this->db->select('*')->from($this->name);
        foreach ($conds as $k => $v) {
            $this->db->where($k . '= :' . $k);
        }
        return $this->db->bindValues($conds)->query();
    }

    public function list($sql) {
        return $this->db->query($sql);
    }

    public function wherelist($sqladd) {
        $sql = 'select * from ' . $this->name . ' ' . $sqladd;
        return $this->db->query($sql);
    }

    public function whereconds($conds) {
        $this->db->select('*')->from($this->name);
        foreach ($conds as $k => $v) {
            $this->db->where($k . '= :' . $k);
        }
        return $this->db->bindValues($conds)->query();
    }

    public function listlimituser($id, $orderby = '', $limit = '') {
        $sql = 'select * from ' . $this->name . ' where user_id=' . $id;
        if ($orderby) {
            $sql .= ' order by ' . $orderby;
        }
        if ($limit) {
            $sql .= ' limit ' . $limit;
        }

        return $this->db->query($sql);
    }

    public function insert($udata, $user_id = NULL) {
        if (!is_null($user_id)) {
            $udata['user_id'] = $user_id;
        }
        return $this->db->insert($this->name)->cols($udata)->query();
    }

    public function update($id, $udata) {
        $this->db->update($this->name)->cols($udata)->where('id=' . $id)->query();
    }

    public function addone($id, $key, $val) {
        $sql = sprintf('update %s set %s=%s+%s where id=%s',
            $this->name, $key, $key, $val, $id
        );
        $this->db->query($sql);
    }

    public function updatewhere($conds, $udata) {
        $this->db->update($this->name)->cols($udata);
        foreach ($conds as $k => $v) {
            $this->db->where($k . '= :' . $k);
        }
        $this->db->bindValues($conds);
        $this->db->query();
    }

    public function count($where) {
        $sql = 'select count(*) as num from ' . $this->name . ' where ' . $where;
        $row = $this->db->row($sql);
        return $row->num;
    }

    public function pagelist($page, $ppp, $where) {
        $count  = $this->count($where);
        $offset = page_get_start($page, $ppp, $count);

        $sql = sprintf(
            'select * from %s where %s order by id desc limit %s offset %s',
            $this->name,
            $where,
            $ppp,
            $offset
        );

        return $this->db->query($sql);
    }

    public function query($sql) {
        return $this->db->query($sql);
    }

    public function rows($cols, $conds) {
        $sql = 'select ' . $cols . ' from ' . $this->name;
        if ($conds) {
            $sql .= ' where ' . $conds;
        }
        return $this->db->query($sql);
    }

    public function totalpage($ppp, $where) {
        $sql      = 'select count(*) as num from ' . $this->name . ' where ' . $where;
        $row      = $this->db->row($sql);
        $totalnum = $row->num;

        return ceil($totalnum / $ppp);
    }

    public function page($page, $ppp, $where, $orderby) {
        $offset = ($page - 1) * $ppp;

        $sql = sprintf(
            'select * from %s where %s %s limit %s offset %s',
            $this->name,
            $where,
            $orderby,
            $ppp,
            $offset
        );

        return $this->db->query($sql);
    }
}
