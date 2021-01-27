<?php
/**
 * @Author Czy
 * @Date 20/09/27
 * @Detail Created by PHPStorm
 */

namespace utils\singleton;

use think\exception\ClassNotFoundException;

class Singleton {
    //创建静态私有的变量保存该类对象
    private static $instance = [];

    //防止使用new直接创建对象
    private function __construct(){}

    //防止使用clone克隆对象
    private function __clone(){}

    /**
     * @param $modelClass
     * @return mixed
     * @Detail Loader Class
     */
    public static function L($modelClass) {
        if(isset(self::$instance[$modelClass])) return self::$instance[$modelClass];
        if (class_exists($modelClass)) $model = new $modelClass();
        else throw new ClassNotFoundException("Class not exists:" . $modelClass, $modelClass);
        return self::$instance[$modelClass] = $model;
    }
}