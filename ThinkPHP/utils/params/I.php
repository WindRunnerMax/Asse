<?php
/**
 * @Author Czy
 * @Date 20/08/16
 * @Detail Created by PHPStorm
 */

namespace utils\params;


use system\exception\ResponseError;

class I {

    /**
     * @param $key
     * @param null $default
     * @param string $filter
     * @return mixed|string
     * @deatil input
     */
    public static function input($key, $default = null, $filter = "") {
        return input($key, $default, $filter);
    }

    /**
     * @param $key
     * @param null $default
     * @param string $filter
     * @return mixed
     */
    public static function get($key, $default = null, $filter = "") {
        return input("get.$key", $default, $filter);
    }

    /**
     * @param $key
     * @param null $default
     * @param string $filter
     * @return mixed
     */
    public static function post($key, $default = null, $filter = "") {
        return input("post.$key", $default, $filter);
    }

    /**
     * @param $key
     * @param null $default
     * @param string $filter
     * @return mixed|string
     * @deatil required input
     */
    public static function requiredInput($key, $default = null, $filter = "") {
        if(input(preg_replace("/\/[sdbaf]/i","","?$key"))){
            return input($key, $default, $filter);
        }else{
            throw new ResponseError(200, "Parameter Error");
        }
    }

    /**
     * @param $key
     * @param null $default
     * @param string $filter
     * @return mixed|string
     * @deatil required get
     */
    public static function requiredGet($key, $default = null, $filter = "") {
        if(input(preg_replace("/\/[sdbaf]/i","","?get.$key"))){
            return input("get.$key", $default, $filter);
        }else{
            throw new ResponseError(200, "Parameter Error");
        }
    }

    /**
     * @param $key
     * @param null $default
     * @param string $filter
     * @return mixed|string
     * @deatil required post
     */
    public static function requiredPost($key, $default = null, $filter = "") {
        if(input(preg_replace("/\/[sdbaf]/i","","?post.$key"))){
            return input("post.$key", $default, $filter);
        }else{
            throw new ResponseError(200, "Parameter Error");
        }
    }

    /**
     * @param $keys
     * @return array
     */
    public static function requiredGetGroup($keys) {
        $params = [];
        foreach ($keys as $key => $value){
            $parsedKey = preg_replace("/\/[sdbaf]/i","", $value);
            $params[$parsedKey] = self::requiredGet($value);
        }
        return $params;
    }

    /**
     * @param $keys
     * @return array
     */
    public static function requiredPostGroup($keys) {
        $params = [];
        foreach ($keys as $key => $value){
            $parsedKey = preg_replace("/\/[sdbaf]/i","", $value);
            $params[$parsedKey] = self::requiredPost($value);
        }
        return $params;
    }
}