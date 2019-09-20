<?php
namespace I;
use M\Middle_Lock;

class Ajax extends Singleton {

    private $r;

    public function __construct() {
        $this->r = (object) [];
    }

    public function getr() {
        return $this->r;
    }

    public function finish($msg) {
        $s = PATH . "\t[GET]";
        $s .= json_encode($_GET) . "\t[POST]";
        $s .= json_encode($_POST) . "\n";
        $s .= $msg;

        $logfile = '/data/errlog/' . date('Ymd');
        if (defined('CONSOLE')) {
            $logfile .= '.console';
        }
        @error_log(date('[H:i:s]') . "\t" . $s . "\n", 3, $logfile);

        $this->assertDialog('系统出现一点儿问题，请联系我们解决.');
    }

    public function nil() {

    }

    //进入业务逻辑处理的时候调用
    public function emsg($msg) {
        Middle_Lock::singleton()->releaseall();

        $this->r->success = true;
        $this->r->err = $msg;
        echo json_encode($this->r);
        exit();
    }

    //不进入业务逻辑
    public function assert($code, $message = "") {
        DB::rollBackTrans();
        Middle_Lock::singleton()->releaseall();

        $this->r->code = $code;
        $this->r->message = $message;
        echo json_encode($this->r, JSON_UNESCAPED_UNICODE);
        exit();
    }

    //不进入业务逻辑 Toast 调用
    public function assertToast($message) {
        $this->assert("assertToast", $message);
    }

    public function assertDialog($message) {
        $this->assert("assertDialog", $message);
    }

    //token验证失败，客户端清理缓存，重新登录
    public function json401($message) {
        $this->assert(401, $message);
    }

    public function normal() {
        Middle_Lock::singleton()->releaseall();

        $this->r->success = true;
        echo json_encode($this->r, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function normalToast($message) {
        $this->r->normalToast = $message;
        $this->normal();
    }

    //不进入逻辑直接跳转
    public function routerPush($name) {
        $this->assert("routerPush", $name);
    }

    public function routerReplace($name) {
        $this->assert("routerReplace", $name);
    }

    public function _storeInit($state, $k) {
        if (!isset($this->r->store)) {
            $this->r->store = array();
        }
        if (!isset($this->r->store[$state])) {
            $this->r->store[$state] = array();
        }
    }

    public function storeInit($state, $k, $v) {
        $this->_storeInit($state, $k);
        $this->r->store[$state][$k] = $v;
    }

    public function storeUpdate($state, $k, $kov, $v = null) {
        $this->_storeInit($state, $k);

        if (is_null($v)) {
            $this->r->store[$state][$k] = $kov;
        } else {
            if (!isset($this->r->store[$state][$k][$kov])) {
                $this->r->store[$state][$k][$kov] = array();
            }
            $this->r->store[$state][$k][$kov] = $v;
        }
    }

}
