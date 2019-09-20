<?php
namespace I;
use Rakit\Validation\Validator;

class Ctl {

    protected $ajax = NULL;
    protected $r = NULL;
    protected $memcached = NULL;

    public function __construct() {
        $this->ajax = Ajax::singleton();
        $this->r = $this->ajax->getr();
    }

    /**
     * 验证输入信息
     * @param  array $rules
     * @return response
     */
    public function validateInput($rules) {
        $validator = new Validator;
        $this->validation = $validator->validate($_POST + $_GET, $rules);
        if ($this->validation->fails()) {
            $errors = $this->validation->errors();
            foreach ($errors->firstOfAll() as $msg) {
                return $this->ajax->assertToast($msg);
            }
        }
    }
}
