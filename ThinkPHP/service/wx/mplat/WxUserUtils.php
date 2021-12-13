<?php
/**
 * @Author Czy
 * @Date 20/12/23
 * @Detail Created by PHPStorm
 */

namespace service\wx\mplat;


use stdClass;
use utils\http\HTTP;

class WxUserUtils {

    /**
     * @param $appId
     * @param $secret
     * @param $code
     * @return bool|stdClass
     */
    public static function getWxSession($appId, $secret, $code) {
        $url = "https://api.weixin.qq.com/sns/jscode2session?".
            "appid=${appId}&secret=${secret}&js_code=${code}&grant_type=authorization_code";
        $wxSession = HTTP::get($url) -> json();
        if(!$wxSession || (isset($wxSession["errcode"]) && $wxSession["errcode"] !== 0)) return false;
        $object = new stdClass();
        $object -> openid = $wxSession["openid"];
        $object -> sessionKey = $wxSession["session_key"];
        return $object;
    }

    public static function getUserPhone($encryptedData, $iv, $sessionKey){
        $aesIV = base64_decode($iv);
        $aesKey = base64_decode($sessionKey);
        $aesCipher = base64_decode($encryptedData);
        try{
            $result = json_decode(openssl_decrypt(
                $aesCipher,
                "AES-128-CBC",
                $aesKey,
                1,
                $aesIV),
                true
            );
            return $result["purePhoneNumber"];
        }catch (\Exception $e){
            return false;
        }
    }

    public static function getMpOpenid($appId, $secret, $code){
        // https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?".
            "appid=${appId}&secret=${secret}&js_code=${code}&grant_type=authorization_code";
        return HTTP::get($url) -> json();
    }

    public static function getMpUserInfo($token, $openid){
        $url = " https://api.weixin.qq.com/sns/userinfo?access_token=${$token}&openid=${$openid}&lang=zh_CN";
        return HTTP::get($url) -> json();
    }

}