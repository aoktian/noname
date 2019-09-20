<?php
namespace I;
use M\Middle_Lock;
use \League\Plates\Engine;

class View extends Singleton {
    public $templates = NULL;
    public $r;

    public function __construct() {
        $this->r         = (object) [];
        $this->templates = new Engine(ROOT_DIR . '/templates');
    }

    public function render($file, $data = []) {
        echo $this->templates->render($file, $data);
        exit();
    }

    public function gethtml($file, $data = []) {
        return $this->templates->render($file, $data);
    }

    public function addData($data) {
        $this->templates->addData($data);
    }

    public function errcall($msg) {
        $s = REQUEST_URI . "\t[POST]";
        $s .= json_encode($_POST) . "\n";
        $s .= $msg;

        $this->error('<pre>' . $s . '</pre>');
    }

    public function html($tpl, $dta = []) {
        Middle_Lock::singleton()->releaseall();
        $this->render($tpl, $dta);
        exit();
    }

    public function display() {
        Middle_Lock::singleton()->releaseall();
        exit();
    }

    public function ajax() {
        Middle_Lock::singleton()->releaseall();
        echo json_encode($this->r, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function error($message) {
        DB::rollBackTrans();
        if ($this->isajax()) {
            $this->r->assertDialog = $this->gethtml('msg', ['content' => $message]);

            $this->ajax();
        } else {
            if ($this->templates) {
                $this->html('error', [
                    'msg' => $message,
                ]);
            } else {
                echo $message;
            }
        }
        exit();
    }

    //断言提示，业务逻辑前置判断
    public function assertToast($msg) {
        DB::rollBackTrans();
        $this->r->assertToast = $msg;
        $this->ajax();
    }

    public function assertAlert($msg) {
        DB::rollBackTrans();
        $this->r->assertAlert = $msg;
        $this->ajax();
    }

    public function assertDialog($msg) {
        DB::rollBackTrans();
        $this->r->assertDialog = $this->gethtml('msg', ['content' => $msg]);
        $this->ajax();
    }

    public function toast($msg) {
        $this->r->toast = $msg;
        $this->ajax();
    }

    public function alert($msg) {
        $this->r->alert = $msg;
        $this->ajax();
    }

    public function msg($message) {
        $this->r->dialog = $this->gethtml('msg', ['content' => $message]);
        $this->ajax();
    }

    public function dialog($tpl, $dta = []) {
        $this->r->dialog = $this->gethtml($tpl, $dta);
        $this->ajax();
    }

    public function redirect($url, $delay = 0) {
        if ($this->isajax()) {
            $this->r->redirect_url = $url;
            $this->ajax();
        } else {
            // header('location:' . $url);
            $this->html('redirect', [
                'delay' => $delay, 'url' => $url,
            ]);
        }
    }

    public function replace_url($url) {
        $this->r->replace_url = $url;
        $this->ajax();
    }

    public function jsstr($s) {
        if (!isset($this->r->script)) {
            $this->r->script = '';
        }
        $this->r->script .= $s;
    }

    public function domhtml($id, $str, $isend = true) {
        if (!isset($this->r->doms)) {
            $this->r->doms = array();
        }

        $this->r->doms[$id] = $str;
        if ($isend) {
            $this->ajax();
        }
    }

    public function dom($id, $tpl, $isend = true) {
        $this->domhtml($id, $this->gethtml($tpl), $isend);
    }

    public static function isajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
