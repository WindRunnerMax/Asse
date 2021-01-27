<?php
/**
 * @Author Czy
 * @Date 21/01/21
 * @Detail Created by PHPStorm
 */

namespace utils\params;


class Audit {
    
    /**
     * @param $str
     * @param $min
     * @param $max
     * @return bool
     */
    public static function auditStrLen($str, $min = null, $max = null) {
        $len = strlen($str);
        if($min !== null && $len < $min) return false;
        if($max !== null && $len > $max) return false;
        return true;
    }

    /**
     * @param $arr
     * @param $n
     * @return bool
     */
    public static function auditPickAtLast($arr, $n) {
        $checkNums = 0;
        foreach ($arr as $key => $value){
            if(!self::auditBlank($value)) ++$checkNums;
        }
        return $checkNums >= $n;
    }

    /**
     * @param $value
     * @param array $arr
     * @return bool
     */
    public static function auditBlank($value, $arr = ["", false, null]) {
        return in_array($value, $arr);
    }

    /**
     * @param $str
     * @return bool
     */
    public static function isNumsStr($str) {
        return is_numeric($str);
    }
}