<?php
/**
 * @Author Czy
 * @Date 21/01/21
 * @Detail Created by PHPStorm
 */

namespace service\wx\mplat;


use utils\http\HTTP;

class Detection {


    public static function checkText($appId, $secret, $text) {
        $token = Token::getAccessToken($appId, $secret);
        $url = "https://api.weixin.qq.com/wxa/msg_sec_check?access_token=${token}";
        $data = ["content" => $text];
        $res = HTTP::post($url, json_encode($data, JSON_UNESCAPED_UNICODE)) -> json();
        return $res["errcode"] === 0;
    }

    public static function checkImg($appId, $secret, $filePath) {
        $token = Token::getAccessToken($appId, $secret);
        $url = "https://api.weixin.qq.com/wxa/img_sec_check?access_token=${token}";
        $data = [new \CURLFile($filePath)];
        $res = HTTP::post($url, $data) -> json();
        return $res["errcode"] === 0;
    }
}