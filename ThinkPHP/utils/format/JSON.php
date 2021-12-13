<?php
/**
 * @author Czy
 * @date 21/05/09
 * @description Created by PHPStorm
 */

namespace utils\format;


class JSON {

    /**
     * @param $str
     * @return array
     * @description detail
     */
    public static function parse($str) {
        return json_decode($str, true);
    }

    /**
     * @param $arr
     * @return false|string
     * @description detail
     */
    public static function stringify($arr) {
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $str
     * @return string
     * @description detail
     */
    public static function charsHanlder($str) {
        return str_replace("&quot;", "\"", $str);
    }
}