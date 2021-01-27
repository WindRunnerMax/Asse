<?php
/**
 * @Author Czy
 * @Date 20/12/22
 * @Detail Created by PHPStorm
 */

namespace service\sms\tencent;
use service\sms\tencent\Config as PackageConfig;
use utils\http\HTTP;

class SMS {

    public static function sendSMS($phone, array $params, $templateId, $templateName){
        $data["Action"]="SendSms";
        $data["PhoneNumberSet.0"] = "+86".$phone;
        foreach ($params as $index => $value){
            $data["TemplateParamSet.".$index] = $value;
        }
        $data["Version"] = "2019-07-11";
        $data["Timestamp"] = time();
        $data["TemplateID"]=  $templateId;
        $data["SmsSdkAppid"] = PackageConfig::$appId;
        $data["Nonce"] = mt_rand(100000,999999);
        $data["Sign"] = $templateName;
        $data["SecretId"] = PackageConfig::$secretId;
        $Signature = self::getSignature($data);
        $data["Signature"] = $Signature;
        $postData = http_build_query($data, null, "&", PHP_QUERY_RFC3986);
        return HTTP::post(PackageConfig::$sendUrl, $postData) -> json();
    }

    private static function getSignature($param) {
        ksort($param);
        $signStr = "POST". PackageConfig::$sendHost ."/?";
        foreach ($param as $key => $value ) {
            $signStr = $signStr . $key . "=" . $value . "&";
        }
        $signStr = substr($signStr, 0, -1);
        $signature = base64_encode(hash_hmac("sha1", $signStr, PackageConfig::$secretKey, true));
        return $signature;
    }
}