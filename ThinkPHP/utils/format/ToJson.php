<?php
/**
 * @Author Czy
 * @Date 20/07/10
 * @Detail Created by PHPStorm
 */

namespace utils\format;


class ToJson {

    /**
     * @param $arr
     * @return false|string
     */
    public static function arrayToJson($arr) {
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
}