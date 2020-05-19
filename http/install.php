<?php
use I\DB;
use M\Middle_Public;
use I\View;

class Controller extends Middle_Public {
    public function __construct() {
        $this->view = View::singleton();
        $this->r    = $this->view->r;
    }

    private function exec_file($path) {
        $content = file_get_contents($path);
        $sqls = explode(";\n", $content);
        foreach ($sqls as $sql) {
            $sql = trim($sql);
            if ($sql) {
                DB::write()->query($sql);
            }
        }
    }

    public function index() {
        if (count(DB::write()->query("show tables")) > 0) {
            echo 'installed';
            return;
        }
        $this->exec_file(ROOT_DIR . '/tools/tables.sql');
        $this->exec_file(ROOT_DIR . '/tools/data.sql');

        echo 'succeed';
    }
}
