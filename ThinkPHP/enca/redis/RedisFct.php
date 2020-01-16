<?php
/**
 * Created by Czy
 * Time: 19/12/26 21:41
 * Detail: *
 */
namespace enca\redis;

use lib\redis\Redis;

class RedisFct{

    private function __construct(){}

    public static function set($key,$value,$expire = ''){
        !Redis::checkRedisConn() && Redis::initRedis();
        Redis::set($key, $value, $expire);
    }

    public static  function get($key){
        !Redis::checkRedisConn() && Redis::initRedis();
        return Redis::get($key);
    }
}