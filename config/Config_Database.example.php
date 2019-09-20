<?php
namespace C;
class Config_Database {
    static $mysql = [
        'host'     => 'xxx',
        'name'     => 'xxx',
        'username' => 'root',
        'password' => 'xxx',
    ];
    static $memcached = [
        ['host' => '127.0.0.1', 'port' => '12001'],
    ];
    static $redis = [
        'host' => '127.0.0.1', 'port' => '6379',
    ];

    static $localmd = [
        ['host' => '127.0.0.1', 'port' => '12001'],
    ];

}
