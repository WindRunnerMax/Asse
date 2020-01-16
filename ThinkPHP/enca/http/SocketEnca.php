<?php
/**
 * Created by Czy
 * Time: 19/12/22 22:51
 * Detail: *
 */

namespace enca\http;


class SocketEnca{

    public static function socketTest($ip = '127.0.0.1',$port='80'){
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_nonblock($socket);
        socket_connect($socket,$ip, $port);
        socket_set_block($socket);
        $r = $w = $f = array($socket);
        $status = socket_select($r, $w, $f, 1);
        socket_close($socket);
        switch($status){
            case 1:
                return true;
            default :
                return false;
        }
    }

}