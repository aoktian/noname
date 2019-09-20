<?php
define('APP', 'admin');
define('IS_ADMIN', 1);

include '../i.php';

$uri = parse_url($_SERVER["REQUEST_URI"]);
define('REQUEST_URI', $_SERVER['REQUEST_URI']);

$path  = $uri['path'];
$paths = explode('/', $path);
$ctl   = isset($paths[1]) && $paths[1] ? $paths[1] : 'index';
$act   = isset($paths[2]) && $paths[2] ? $paths[2] : 'index';
unset($paths[0]);
unset($paths[1]);
unset($paths[2]);

define('CONTROLLER', $ctl);
define('ACTION', $act);
define('PATH', '/' . $ctl . '/' . $act);
define('IP', get_ip());

$http = ROOT_DIR . '/http/' . $ctl . '.php';
if (!file_exists($http)) {
    exit($ctl . ' controller is not exists.');
}

include $http;
$controller = Controller::singleton();
if (!method_exists($controller, $act)) {
    echo 'action not exists.';
    exit();
}
$controller->$act(...$paths);
