<?php
/**
 * @Author Czy
 * @Date 20/07/10
 * @Detail Created by PHPStorm
 */

namespace utils\regex;


class RegExp {

    /**
     * @param $pattern
     * @param $str
     * @return mixed
     */
    public static function match($pattern, $str) {
        preg_match($pattern,$str,$result);
        return $result;
    }

    /**
     * @param $pattern
     * @param $str
     * @return mixed
     */
    public static function matchAll($pattern, $str) {
        preg_match_all($pattern,$str,$result);
        return $result;
    }

}
