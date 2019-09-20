<?php
use I\Table;
use M\Middle_Public;

class Controller extends Middle_Public {
    public function index() {
        return $this->view->redirect('/task/ido');
    }

    public function login() {
        $email = getgpc('email');
        if (!$email) {
            return $this->view->html('login');
        }

        $password = getgpc('password');
        if (!$password) {
            return $this->view->html('login');
        }

        $user = Table::singleton('users')->findone(['email' => $email]);
        if (!$user) {
            $this->view->error('不存在');
        }

        if (!password_verify($password, $user->password)) {
            $this->view->error('密码不正确');
        }

        $segment = $this->getsegment();
        $segment->set('authed', $user);

        $backurl = $segment->get('backurl');
        if ($backurl) {
            return $this->view->redirect($backurl);
        }
        return $this->view->redirect('/task/ido');
    }

    public function logout() {
        $segment = $this->getsegment();
        $segment->set('authed', NULL);

        $this->view->html('login');
    }

}
