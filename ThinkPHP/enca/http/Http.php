<?php
/**
 * Created by Czy
 * Time: 19/12/22 22:20
 * Detail: *
 */

namespace enca\http;

class Http {

    /**
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $headers
     * @return string
     */
    public static function httpRequest($url, $data = [], $method = 'GET', $headers = []) {
        $res = self::curlHTTP($url, $data, $method, $headers, false);
        curl_close($res[0]);
        return $res[1];
    }

    public static function httpRequestWithHeaders($url, $data = [], $method = 'GET', $headers = []) {
        $res = self::curlHTTP($url, $data, $method, $headers, true);
        $result = self::resultDispose($res[0], $res[1]);
        curl_close($res[0]);
        return $result;
    }

    public static function sendStatus($code = '404') {
        header("HTTP/1.1 $code");
        exit(0);
    }

    private static function curlHTTP($url, $data, $method, $headers ,$cookieFlag) {
        self::headersDispose($headers);
        $curl = self::getCurl($url, $data, $method, $headers, $cookieFlag);
        $result = curl_exec($curl);    //执行操作
        // Log::write($result);
        // if(curl_errno($curl)) Log::write(curl_error($curl)."\n".$url."\n".implode("\n", $headers),'ERROR');
        return [$curl,$result];
    }

    private static function headersDispose(&$headers) {
        $headerDisposeCurl = [];
        if (!isset($headers['X-FORWARDED-FOR'])) {
            $ipArr = [mt_rand(1, 255), mt_rand(1, 255), mt_rand(1, 255), mt_rand(1, 255)];
            $headers["X-FORWARDED-FOR"] = $ipArr[0] . "." . $ipArr[1] . "." . $ipArr[2] . "." . $ipArr[3];
            $headers["CLIENT-IP"] = $headers["X-FORWARDED-FOR"];
        }
        foreach ($headers as $key => $value) {
            array_push($headerDisposeCurl, $key . ':' . $value);
        }
        $headers = $headerDisposeCurl;
        return true;
    }

    private static function getCurl($url, $data, $method, $headers, $cookieFlag) {
        $curl = curl_init();  // 启动一个CURL会话
        if (count($headers) >= 1) curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if ($method == 'GET') $url = self::urlDispose($url, $data);
        curl_setopt($curl, CURLOPT_URL, $url);  // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);  // 自动设置Referer
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            if ($data != '') curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);  // 设置超时限制防止死循环
        if ($cookieFlag) curl_setopt($curl, CURLOPT_HEADER, 2);  // 显示返回的Header区域内容
        else curl_setopt($curl, CURLOPT_HEADER, 0);  // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   // 获取的信息以文件流的形式返回
        return $curl;
    }

    private static function resultDispose($curl, $result) {
        $responseHeader_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeadersString = substr($result, 0, $responseHeader_size);
        $responseBody = substr($result, $responseHeader_size);
        $responseHeadersArr = explode("\n", $responseHeadersString);
        $responseHeaders = [];
        foreach ($responseHeadersArr as $value) {
            $info = explode(": ", $value);
            if (count($info) >= 2) $responseHeaders[$info[0]] = $info[1];
        }
        return [$responseHeaders, $responseBody];
    }

    private static function urlDispose($url, $arr) {
        $ret = "";
        foreach ($arr as $key => $value) {
            $ret = $ret."&".$key."=".$value;
        }
        if ($ret !== "") $url = $url . "?" . $ret;
        return $url;
    }



}
