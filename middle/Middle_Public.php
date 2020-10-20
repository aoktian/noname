<?php
namespace M;
use I\DB;
use I\Singleton;
use I\View;
use Rakit\Validation\Validator;

class Middle_Public extends Singleton {
    protected $segment;

    public $view = NULL;
    public $r    = NULL;
    public function __construct() {
        $this->view = View::singleton();
        $this->r    = $this->view->r;

        $this->checkAuth();
    }

    public function checkAuth() {
        // todo check session
        $segment = $this->getsegment();
        $authed  = $segment->get('authed');
        if (!$authed) {
            if (!$this->view->isajax()) {
                $segment->set('backurl', $_SERVER['REQUEST_URI']);
            }
            return $this->view->redirect('/index/login');
        }

        $id   = $authed->id;
        $user = DB::write()->row("select * from users where id = $id");

        if (!$user || $user->password != $authed->password) {
            $segment->set('backurl', $_SERVER['REQUEST_URI']);
            return $this->view->redirect('/index/login');
        }

        $this->authed = $user;
        $this->view->templates->addData(['authed' => $user]);
    }

    public function getsegment() {
        if (!$this->segment) {
            $session_factory = new \Aura\Session\SessionFactory;
            $session         = $session_factory->newInstance($_COOKIE);
            $this->segment   = $session->getSegment('admin');
        }

        return $this->segment;
    }

    /**
     * 验证输入信息
     * @param  array $rules
     * @return response
     */
    public function validateInput($rules, $find = NULL) {
        $validator        = new Validator;
        $this->validation = $validator->validate(($find ? $find : $_POST + $_GET), $rules);
        if ($this->validation->fails()) {
            $errors = $this->validation->errors();
            foreach ($errors->firstOfAll() as $msg) {
                return $this->view->error($msg);
            }
        }
    }

}
