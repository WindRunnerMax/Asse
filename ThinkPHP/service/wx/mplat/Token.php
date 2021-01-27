<?php
namespace service\wx\mplat;
use utils\http\HTTP;
use utils\redis\RedisUtil;
use config\Config;
use think\Controller;
use think\Log;


class Token extends Controller {

    public static function verifyHost(){
        echo input("get.echostr");
    }


    /**
     * @param $appId
     * @param $secret
     * @return string
     */
    public static function getAccessToken($appId, $secret){
        $accessToken = RedisUtil::get("PubAccessToken-${appId}");
        if ($accessToken === false) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=${appId}&secret=${secret}";
            $accessTokenObject = HTTP::get($url) -> json();
            $accessToken = $accessTokenObject["access_token"];
            RedisUtil::set("PubAccessToken-${appId}", $accessToken, 3600);
        }
        return $accessToken;
    }
}
