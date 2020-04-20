<?php
namespace utils\model;
use think\exception\ClassNotFoundException;
use think\Model;

/**
 * @Author Czy
 * @Date 20/04/07
 * @Detail Model实例化单例类 ModelUtil
 */

class M {

    //创建静态私有的变量保存该类对象
    private static $instance = [];

    //防止使用new直接创建对象
    private function __construct(){}

    //防止使用clone克隆对象
    private function __clone(){}

    /**
     * @param $name
     * @return Model
     * @Detail Loader Model
     */
    public static function L($name) {
        $modelClass = "\\model\\${name}";
        if(isset(self::$instance[$modelClass])) return self::$instance[$modelClass];
        if (class_exists($modelClass)) $model = new $modelClass();
        else throw new ClassNotFoundException('class not exists:' . $modelClass, $modelClass);
        return self::$instance[$modelClass] = $model;
    }

}