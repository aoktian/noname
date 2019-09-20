<?php
namespace I;
use C\Config_Database;
use Workerman\MySQL\Connection;

class DB {
    private static $writedb = NULL;
    public static function write() {
        if (self::$writedb) {
            return self::$writedb;
        }

        $config = (object) Config_Database::$mysql;
        self::$writedb = new Connection($config->host,
            3306,
            $config->username,
            $config->password,
            $config->name
        );
        return self::$writedb;
    }

    public static function beginTrans() {
        $db = self::write();
        $db->beginTrans();
    }

    public static function commitTrans() {
        $db = self::write();
        $db->commitTrans();
    }

    public static function rollBackTrans() {
        if (!self::$writedb) {
            return;
        }
        $db = self::write();
        $db->rollBackTrans();
    }

    public static function find($table, $id) {
        return self::write()->select('*')->from($table)->where('id= :id')->bindValues(array('id' => $id))->row();
    }

    public static function all($table) {
        return self::write()->query("select * from $table");
    }

    public static function keyBy($sql) {
        $rtn = array();
        $rows = self::write()->query($sql);
        foreach ($rows as $row) {
            $rtn[$row->id] = $row;
        }

        return $rtn;
    }

}
