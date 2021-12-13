<?php
/**
 * @Author Czy
 * @Date 20/08/24
 * @Detail Created by PHPStorm
 */

namespace utils\params;


use think\exception\HttpException;

class P {

    /**
     * @param $arr
     * @param $key
     * @param string $default
     * @return string
     * @deatil sage param
     */
    public static function safeKey($arr, $key, $default = "") {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }

    /**
     * @param $arr
     * @param $key
     * @return string
     * @deatil sage param
     */
    public static function requiredKey($arr, $key) {
        if(isset($arr[$key])) return $arr[$key];
        else throw new HttpException(json(["status" => 0,"msg" => "System Hint"],200));
    }
}