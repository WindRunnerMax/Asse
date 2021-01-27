<?php
namespace service\wx\mplat;

use utils\http\HTTP;

class Service {
    /**
     * @param $appId
     * @param $secret
     * @param $openid
     * @param $type
     * @param $content
     * @return string
     */
    public static function sendCustomMsg($appId, $secret, $openid, $type, $content){
        $accessToken = Token::getAccessToken($appId, $secret);
        $data = [
                "touser" => $openid,
                "msgtype" => $type,
                $type => ["content" => $content]
        ];
        $data = json_encode($data);
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
        return HTTP::post($url ,$data) -> json();
    }

    /**
     * @param $appId
     * @param $secret
     * @param $data
     * @return string
     */
    public static function sendTemplateMsg($appId, $secret, $data){
        $accessToken = Token::getAccessToken($appId, $secret);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accessToken";
        return HTTP::post($url ,$data);
    }
}