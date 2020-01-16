<?php
/**
 * Created by Czy
 * Time: 19/12/22 22:59
 * Detail: *
 */

namespace enca\appfct;


class SessionFct {

    public static function checkSeesion($key) {
        !isset($_SESSION) && session_start(); //检查Seesion Start
        if (isset($_SESSION[$key])) return $_SESSION[$key];
        else abort(200,'{ code: 0 , "msg": "System Hint" }');
    }
}