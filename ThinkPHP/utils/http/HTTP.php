<?php
/**
 * @Author Czy
 * @Date 19/12/22
 * @Detail Created by PHPStorm
 */

namespace utils\http;

class HTTP {

    /**
     * @param $url
     * @param array $data
     * @param array $headers
     * @param null $config
     * @return Response
     */
    public static function get($url, $data = [], $headers = [], $config = null) {
        return self::request($url, $data, "GET", $headers, $config);
    }

    /**
     * @param $url
     * @param array $data
     * @param array $headers
     * @param $config
     * @return Response
     */
    public static function post($url, $data = [], $headers = [], $config = null) {
        return self::request($url, $data, "POST", $headers, $config);
    }

    /**
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $headers
     * @param null $config
     * @return Response
     */
    public static function request($url, $data = [], $method = "GET", $headers = [], $config = null) {
        static $default = ["mode" => "normal", "timeout" => 3, "proxy" => null];
        $config = $config ? array_merge($default, $config) : $default;
        try{
            $res = self::curlHTTP($url, $data, $method, $headers, $config);
            $result = self::resultDispose($res["curl"], $res["res"]);
            return new Response($headers, $result["headers"], $result["body"]);
        }finally{
            curl_close($res["curl"]);
        }
    }

    private static function urlDispose($url, $arr) {
        $ret = "";
        foreach ($arr as $key => $value) {
            $ret = $ret."&".$key."=".$value;
        }
        if ($ret !== "") $url = $url . "?" . $ret;
        return $url;
    }

    public static function getNormalHeader(){
        return [
            "Expect" => "",
            "accept-encoding" => "deflate, br",
            "accept-language" => "zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2",
            "cache-control" => "max-age=0",
            "User-Agent" => "Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )",
        ];
    }

    private static function curlHTTP($url, $data, $method, &$headers, $config) {
        $method = strtoupper($method);
        self::headersDispose($headers);
        if($method === "POST"){
            if($config["mode"] === "json") $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            if($config["mode"] === "query") $data = http_build_query($data, null, "&", PHP_QUERY_RFC3986);
        }
        $curl = self::getCurl($url, $data, $method, $headers, $config);
        $result = curl_exec($curl);    //执行操作
        // Log::write($result);
        // if(curl_errno($curl)) Log::write(curl_error($curl)."\n".$url."\n".implode("\n", $headers),"ERROR");
        return ["curl" => $curl,"res" => $result];
    }

    private static function headersDispose(&$headers) {
        $headerDisposeCurl = [];
        if(count($headers) === 0) $headers = self::getNormalHeader();
        if (!isset($headers["X-FORWARDED-FOR"])) {
            $ipArr = [mt_rand(1, 255), mt_rand(1, 255), mt_rand(1, 255), mt_rand(1, 255)];
            $headers["X-FORWARDED-FOR"] = $ipArr[0] . "." . $ipArr[1] . "." . $ipArr[2] . "." . $ipArr[3];
            $headers["CLIENT-IP"] = $headers["X-FORWARDED-FOR"];
        }
        foreach ($headers as $key => $value) {
            array_push($headerDisposeCurl, $key . ":" . $value);
        }
        $headers = $headerDisposeCurl;
        return true;
    }

    private static function getCurl($url, $data, $method, $headers, $config) {
        $curl = curl_init();  // 启动一个CURL会话
        if (count($headers) >= 1) curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if ($method == "GET") $url = self::urlDispose($url, $data);
        curl_setopt($curl, CURLOPT_URL, $url);  // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);  // 自动设置Referer
        if ($method == "POST") {
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  // Post提交的数据包
        }
        if($config["proxy"]){
            curl_setopt($curl, CURLOPT_PROXYTYPE, $config["proxy"]["type"]);
            curl_setopt($curl, CURLOPT_PROXY, $config["proxy"]["url"]);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, $config["timeout"]);  // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 2);  // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   // 获取的信息以文件流的形式返回
        return $curl;
    }

    private static function resultDispose($curl, $result) {
        $responseHeaderSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeadersString = substr($result, 0, $responseHeaderSize);
        $responseBody = substr($result, $responseHeaderSize);
        $responseHeadersArr = explode("\r\n", $responseHeadersString);
        $responseHeaders = ["code" => curl_getinfo($curl,CURLINFO_HTTP_CODE)];
        foreach ($responseHeadersArr as $value) {
            $info = explode(": ", $value);
            if(count($info) >= 2) $responseHeaders[strtolower($info[0])] = $info[1];
        }
        return ["headers" => $responseHeaders, "body" => $responseBody];
    }

}
