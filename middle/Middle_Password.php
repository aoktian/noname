<?php
namespace M;
use I\Singleton;

class Middle_Password extends Singleton {

    public function hash( $password ) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function generate( $length = 8 ) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $password = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        return $password;
    }

}
