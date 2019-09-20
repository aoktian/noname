<?php
namespace I;
use C\Config_Database;
use Memcached;
use Redis;

class Proxy extends Singleton {

    public function getuser($user_id) {
        $db = DB::write();
        return DB::find(Table::USERS, $user_id);
    }

    private $memcached = NULL;
    public function getmemcached() {
        if ($this->memcached) {
            return $this->memcached;
        }
        $this->memcached = new Memcached();
        $conf            = Config_Database::$memcached;
        foreach ($conf as $v) {
            $this->memcached->addServer($v['host'], $v['port']);
        }
        return $this->memcached;
    }

    private $redis = NULL;
    public function getRedis() {
        if ($this->redis) {
            return $this->redis;
        }

        $config = Config_Database::$redis;
        $redis  = new Redis();
        $redis->connect($config['host'], $config['port']);

        $this->redis = $redis;
        return $this->redis;
    }

    private $localmd = NULL;
    public function getlocalmd() {
        if ($this->localmd) {
            return $this->localmd;
        }
        $this->localmd = new Memcached();
        $conf          = Config_Database::$localmd;
        foreach ($conf as $v) {
            $this->localmd->addServer($v['host'], $v['port']);
        }
        return $this->localmd;
    }
}
