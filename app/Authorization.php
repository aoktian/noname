<?php
namespace I;

class Authorization extends Singleton {

    const SUB_WX = 'wx';
    const SUB_ADMIN = 'admin';

    const KEY = 'gUjZPqo0HNwCwmO5L3LKCG3vcsVbuA8L';

    public function getToken($user_id, $tel, $sub, $ischktime) {
        $payload = array(
            'data' => [
                'user_id' => $user_id,
                'tel' => $tel,
                't' => RUNTIME,
            ],
            'iss' => "http://51818198.com",
            'sub' => $sub,
        );

        // default HS256 algorithm
        $token = jwt_encode($payload, self::KEY);

        if ($ischktime) {
            $memcached = Proxy::singleton()->getmemcached();
            $memcached->set('tokenrtime_' . $user_id, RUNTIME);
        }

        return $token;
    }

    public function verify($sub, $ischktime = false) {
        $token = $this->getclienttoken();

        try {
            $decoded_token = jwt_decode($token, self::KEY);
        } catch (Exception $e) {
            return -1;
        }

        if (!$decoded_token) {
            return -2;
        }

        if ($decoded_token['sub'] !== $sub) {
            return -3;
        }

        $user_id = $decoded_token['data']['user_id'];
        if ($ischktime) {
            $memcached = Proxy::singleton()->getmemcached();
            $mkey = 'tokenrtime_' . $user_id;
            $lasttime = $memcached->get($mkey);
            if (!$lasttime || RUNTIME - $lasttime > 3000) {
                return -4; //操作过期
            }
            $memcached->set($mkey, RUNTIME);
        }

        return $user_id;
    }

    public function getclienttoken() {
        $token = getgpc('access_token');
        if ($token) {
            return $token;
        }

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                if ($header == 'Authorization') {
                    return $value;
                }
            }
        }
        return 'Bearer ';
    }

    public function assertPassword($input, $password) {
        if (!password_verify($input, $password)) {
            $ajax = Ajax::singleton();
            $ajax->assertToast('输入的密码不正确');
        }
    }

}
