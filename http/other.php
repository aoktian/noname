<?php
use M\Middle_Public;

class Controller extends Middle_Public {
    public function abcx($name) {
        return $this->view->html($name);
    }
}
