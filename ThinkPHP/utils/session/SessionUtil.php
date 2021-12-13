<?php
/**
 * Created by Czy
 * Time: 19/12/22 22:59
 * Detail: *
 */

namespace utils\session;


use system\exception\ResponseError;

class SessionUtil {

    /**
     * @var bool
     */
    private static $start = false;

    /**
     * @return bool
     * @description detail
     */
    public static function sessionStart(){
        if(self::$start) return true;
        !isset($_SESSION) && session_start();   //检查Session Start
        self::$start = true;
        return true;
    }

    /**
     * @param $key
     * @return mixed
     * @description detail
     */
    public static function checkSession($key) {
        self::sessionStart();
        if(isset($_SESSION[$key])) return $_SESSION[$key];
        else throw new ResponseError(200, "Auth Error");
    }

    /**
     * @param $key
     * @param string $default
     * @return string
     * @description detail
     */
    public static function get($key, $default = "") {
        return self::safeGet($key, $default);
    }

    /**
     * @param $key
     * @param string $default
     * @return string
     * @description detail
     */
    public static function safeGet($key, $default = "") {
        self::sessionStart();
        if(isset($_SESSION[$key])) return $_SESSION[$key];
        return $default;
    }

    /**
     * @param $key
     * @return mixed
     * @description detail
     */
    public static function requiredGet($key) {
        return self::checkSession($key);
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     * @description detail
     */
    public static function set($key, $value) {
        self::sessionStart();
        $_SESSION[$key] = $value;
        return true;
    }

    /**
     * @param $key
     * @return bool
     * @description detail
     */
    public static function exist($key) {
        self::sessionStart();
        return isset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @return bool
     * @description detail
     */
    public static function delete($key) {
        self::sessionStart();
        unset($_SESSION[$key]);
        return true;
    }
}