<?php
/**
 * Created by Czy
 * Time: 19/12/22 22:59
 * Detail: *
 */

namespace utils\session;


class SessionUtil {

    private static $start = false;

    public static function sessionStart(){
        if(self::$start) return true;
        !isset($_SESSION) && session_start();   //检查Session Start
        self::$start = true;
        return true;
    }

    public static function checkSession($key) {
        self::sessionStart();
        if(isset($_SESSION[$key])) return $_SESSION[$key];
        else abort(200,"{ status: 0 , \"msg\": \"System Hint\" }");
        return "";
    }

    public static function safeGet($key) {
        self::sessionStart();
        if (isset($_SESSION[$key])) return $_SESSION[$key];
        return "";
    }

    public static function requiredGet($key) {
        return self::checkSession($key);
    }

    public static function set($key, $value) {
        self::sessionStart();
        $_SESSION[$key] = $value;
        return true;
    }
}