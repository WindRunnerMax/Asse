<?php
/**
 * @Author Czy
 * @Date 20/07/10
 * @Detail Created by PHPStorm
 */

namespace utils\format;


class DateTime {

    /**
     * @param string $pattern
     * @param bool $time
     * @return false|string
     */
    public static function format($pattern = "Y-m-d H:i:s", $time=null){
        date_default_timezone_set("Asia/Shanghai");
        if(!$time) $time = time();
        return date($pattern, $time);
    }

    /**
     * @param $str
     * @return false|int
     * @exapmle 2021-01-14 01:00:00 | +1 day | ...
     */
    public static function timestamp($str) {
        return strtotime($str);
    }
}
