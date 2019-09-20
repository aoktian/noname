<?php
namespace I;
class Singleton {
    static $instances = [];
    static public function singleton(){
        $name =  get_called_class();
        if( !isset( self::$instances[$name] ) ){
            self::$instances[$name] = new $name();
        }
        return self::$instances[$name];
    }
}
