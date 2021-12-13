<?php
/**
 * @Author Czy
 * @Date 21/02/09
 * @Detail Created by PHPStorm
 */

namespace utils\http;


use config\HTTPConfig;
use utils\params\P;

class Transfer {

    /**
     * @param $url
     * @param array $data
     * @param array $headers
     * @param array $config
     * @return Response
     */
    public static function get($url, $data = [], $headers = [], $config = null) {
        return self::request($url, $data, "GET", $headers, $config);
    }

    /**
     * @param $url
     * @param array $data
     * @param array $headers
     * @param array $config
     * @return Response
     */
    public static function post($url, $data = [], $headers = [], $config = null) {
        return self::request($url, $data, "POST", $headers, $config);
    }

    /**
     * @param $url
     * @param $toUrl
     * @param array $data
     * @param string $method
     * @param array $headers
     * @param array $config
     * @return Response
     */
    public static function request($url, $data = [], $method = "GET", $headers = [], $config = null){
        list($toUrl, $toData, $toMethod, $toHeaders) = HTTPConfig::getTransferConfig();
        $body = serialize([
            "url" => $url,
            "data" => $data,
            "method" => $method,
            "headers" => $headers
        ]);
        $data = [
            "body-retain" => $body,
            "sign-retain" => md5($body.HTTPConfig::$secret)
        ];
        $toData = array_merge($toData, $data);
        $res = HTTP::request($toUrl, $toData, $toMethod, $toHeaders, $config);
        $resHeaders = $res->getAllHeaders();
        $resHeaders["code"] = (int)$resHeaders["code"];
        return new Response($toHeaders, $resHeaders, $res->text());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function convertToOrigin(){
        $body = P::requiredKey($_POST, "body-retain");
        $sign = P::requiredKey($_POST, "sign-retain");
        if(md5($body.HTTPConfig::$secret) !== $sign) throw new \Exception("ERROR");
        $body = unserialize($body);
        $res = HTTP::request($body["url"], $body["method"], $body["data"], $body["headers"]);
        return new think\Response($res->text(), 200, $res->getAllHeaders());
    }

}