<?php
/**
 * Created by Czy
 * Time: 19/12/26 21:41
 * Detail: *
 */
namespace utils\redis;

use system\dependencies\redis\Redis;

class RedisUtil{

    private function __construct(){}

    private static function connect(){
        !Redis::checkRedisConn() && Redis::initRedis(["db" => 2]);
    }

    public static function set($key, $value, $expire = ""){
        self::connect();
        Redis::set($key, $value, $expire);
    }

    public static  function get($key){
        self::connect();
        return Redis::get($key);
    }
}