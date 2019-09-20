<?php
function getgpc($k, $v = NULL) {
    if (isset($_GET[$k])) {
        return $_GET[$k];
    } elseif (isset($_POST[$k])) {
        return $_POST[$k];
    }
    return $v;
}

function is_weixin() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }

    return false;
}

function aget($a, $k, $default = NULL) {
    if (array_key_exists($k, $a)) {
        return $a[$k];
    } else {
        return $default;
    }
}

function removehost($content) {
    return str_replace('http://' . $_SERVER['HTTP_HOST'], '', $content);
}

function page_get_start($page, $ppp, $totalnum) {
    $totalpage = ceil($totalnum / $ppp);
    $page      = max(1, min($totalpage, intval($page)));
    return ($page - 1) * $ppp;
}

function cutstr($string, $length, $dot = ' ...') {
    if (strlen($string) <= $length) {
        return $string;
    }

    $pre    = chr(1);
    $end    = chr(1);
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);

    $strcut = '';
    $n      = $tn      = $noc      = 0;
    while ($n < strlen($string)) {

        $t = ord($string[$n]);
        if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
            $tn = 1;
            $n++;
            $noc++;
        } elseif (194 <= $t && $t <= 223) {
            $tn = 2;
            $n += 2;
            $noc += 2;
        } elseif (224 <= $t && $t <= 239) {
            $tn = 3;
            $n += 3;
            $noc += 2;
        } elseif (240 <= $t && $t <= 247) {
            $tn = 4;
            $n += 4;
            $noc += 2;
        } elseif (248 <= $t && $t <= 251) {
            $tn = 5;
            $n += 5;
            $noc += 2;
        } elseif ($t == 252 || $t == 253) {
            $tn = 6;
            $n += 6;
            $noc += 2;
        } else {
            $n++;
        }

        if ($noc >= $length) {
            break;
        }

    }
    if ($noc > $length) {
        $n -= $tn;
    }

    $strcut = substr($string, 0, $n);

    $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

    $pos = strrpos($strcut, chr(1));
    if ($pos !== false) {
        $strcut = substr($strcut, 0, $pos);
    }
    return $strcut . $dot;
}

function get_ip() {
    //判断服务器是否允许$_SERVER
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $realip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        //不允许就使用getenv获取
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    return $realip;
}

function getaddr($address) {
    return $address->province . $address->city . $address->county . $address->detail;
}

function keyBy($a, $k = 'id') {
    $rtn = array();
    foreach ($a as $v) {
        $rtn[$v->$k] = $v;
    }
    return $rtn;
}

function keyBy2($a, $k, $k2) {
    $rtn = array();
    foreach ($a as $v) {
        if (!isset($rtn[$v->$k])) {
            $rtn[$v->$k] = array();
        }
        $rtn[$v->$k][$v->$k2] = $v;
    }
    return $rtn;
}

function time2string($second) {
    $day    = floor($second / (3600 * 24));
    $second = $second % (3600 * 24); //除去整天之后剩余的时间
    $hour   = floor($second / 3600);
    $second = $second % 3600; //除去整小时之后剩余的时间
    $minute = floor($second / 60);
    $second = $second % 60; //除去整分钟之后剩余的时间
    //返回字符串
    $s = '';
    if ($day > 0) {
        $s .= $day . '日';
    }
    if ($hour > 0) {
        $s .= $hour . '时';
    }
    if ($day == 0 && $minute > 0) {
        $s .= $minute . '分';
    }

    return $s;
}

function getlast($task) {
    if ($task->status > 12) {
        return '-';
    }

    if (RUNTIME < $task->deadline) {
        return time2string($task->deadline - RUNTIME);
    } else {
        return '葱爆';
    }
}

function tr_color($task) {
    if (98 == $task->status) {
        return 'text-success';
    } elseif (99 == $task->status) {
        return 'text-muted';
    } else {
        if ($task->status <= 12 && RUNTIME > $task->deadline) {
            return 'shan_bg';
        } else {
            $textcolor = I\App::singleton()->getconfig('worktime', 'priority_color');
            return $textcolor[$task->priority];
        }
    }
}
