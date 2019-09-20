<?php
namespace M;
use I\Proxy;
use I\Singleton;

class Middle_Lock extends Singleton {
    private $locks = [];

    public function start($name) {
        if (isset($this->locks[$name])) {
            return;
        }

        $lock = new \Yurun\Until\Lock\Memcached(
            $name,
            Proxy::singleton()->getmemcached(),
            0, // 获得锁等待超时时间，单位：毫秒，0为不限制，留空则为默认值
            100, // 获得锁每次尝试间隔，单位：毫秒，留空则为默认值
            10// 锁超时时间，单位：秒，留空则为默认值
        );
        $this->locks[$name] = $lock;

        $lock->lock();
    }

    public function finish($name) {
        if (!isset($this->locks[$name])) {
            return;
        }
        $lock = $this->locks[$name];
        unset($this->locks[$name]);

        $lock->unlock();
    }

    public function releaseall() {
        foreach ($this->locks as $lock) {
            $lock->unlock();
        }
        $this->locks = [];
    }

    public function user($id) {
        $this->start('user_' . $id);
    }
}
