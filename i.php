<?php
define('ROOT_DIR', dirname(__FILE__));
define('TODAY', date('Ymd'));

require ROOT_DIR . '/vendor/autoload.php';
use I\View;

date_default_timezone_set('Asia/Shanghai');
define('RUNTIME', time());

//获取所有的error
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $msg = "$errstr $errfile $errline\n";

    View::singleton()->errcall($msg);
});
set_exception_handler(function ($exception) {
    View::singleton()->errcall($exception);
});
