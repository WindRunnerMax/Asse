<?php

/**
 * @Author Czy
 * @Date 20/09/27
 * @Detail 客户端类
 */

namespace utils\swoole;
use utils\http\HTTP;

class Client{

    private $client = null;
    
    function __construct() {
        $this -> client = new \Swoole\Client(SWOOLE_SOCK_TCP);
        if(!$this -> client -> connect("127.0.0.1", 9501, 1)) HTTP::sendStatus(500);
    }

    public function getClient(){
        return $this -> client;
    }

    function __destruct(){
        $this -> client -> close();
    }
}