<?php
namespace I;
class App extends Singleton {
    private $configs = array();
    public function getconfig($name, $key = NULL) {
        if (!isset($this->configs[$name])) {
            $this->configs[$name] = include ROOT_DIR . '/config/' . $name . '.php';
        }

        if (!$key) {
            return $this->configs[$name];
        }

        return $this->configs[$name][$key];
    }

}
