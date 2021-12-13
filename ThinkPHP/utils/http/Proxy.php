<?php
/**
 * @Author Czy
 * @Date 21/02/09
 * @Detail Created by PHPStorm
 */

namespace utils\http;


use config\HTTPConfig;

class Proxy {

    /**
     * @param $url
     * @param array $data
     * @param array $headers
     * @param array $config
     * @return Response
     */
    public static function get($url, $data = [], $headers = [], $config = []) {
        return self::request($url, $data, "GET", $headers, $config);
    }

    /**
     * @param $url
     * @param array $data
     * @param array $headers
     * @param array $config
     * @return Response
     */
    public static function post($url, $data = [], $headers = [], $config = []) {
        return self::request($url, $data, "POST", $headers, $config);
    }

    /**
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $headers
     * @param array $config
     * @return Response
     */
    public static function request($url, $data = [], $method = "GET", $headers = [], $config = []){
        $config["proxy"] = HTTPConfig::$proxy;
        return HTTP::request($url, $data, $method, $headers, $config);
    }
}