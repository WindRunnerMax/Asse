<?php
/**
 * @Author Czy
 * @Date 20/11/03
 * @Detail Created by PHPStorm
 */

namespace service\wx\pay;
use config\Config as GlobalConfig;
use think\Response;
use utils\http\HTTP;
use service\wx\pay\Config as PackageConfig;

class PayUtils {

    public static function submitOrder($body, $openid, $fee) {
        $post["appid"] = GlobalConfig::$APPID;
        $post["body"] = $body;
        $post["mch_id"] = PackageConfig::$mchId;
        $post["nonce_str"] = self::getRandStr();
        $post["notify_url"] = PackageConfig::$notify;
        $post["openid"] = $openid;
        $post["out_trade_no"] = self::getOrderNumber();
        $post["spbill_create_ip"] = $_SERVER["REMOTE_ADDR"];
        $post["total_fee"] = $fee;
        $post["trade_type"] = "JSAPI";
        $post["sign"] = self::sign($post);
        $postXml = self::arrayToXml($post);
        $data = HTTP::post(PackageConfig::$submitUrl, $postXml) -> text();
        if(!$data) return ["RETURN_CODE" => "", "RESULT_CODE" => ""];
        $info = self::xmlToArray($data);
        $info["order_no"] = $post["out_trade_no"];
        $info["nonce_str"] = $post["nonce_str"];
        return $info;
    }

    public static function refund($orderNo, $fee) {
        $param = [
            "appid" => GlobalConfig::$APPID,
            "mch_id" => PackageConfig::$mchId,
            "nonce_str" => self::getRandStr(),
            "out_refund_no" => $orderNo,
            "out_trade_no" => $orderNo,
            "total_fee" => $fee,
            "refund_fee" => $fee,
        ];
        $param["sign"] = self::getPaySign($param);
        $xml = self::arrayToXml($param);
        $resultXml = self::postXmlSSLCurl(PackageConfig::$refundUrl, $xml);
        $result = self::xmlToArray($resultXml);
        return $result;
    }

    /**
     * @param $xml
     * @return array
     */
    public static function acceptXmlToData($xml) {
        $disableEntities = libxml_disable_entity_loader(true);
        $notifyInfo = (array)simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        libxml_disable_entity_loader($disableEntities);
        return  $notifyInfo;
    }


    /**
     * @return Response
     */
    public static function success(){
        $return["return_code"] = "SUCCESS";
        $return["return_msg"] = "OK";
        $xml = "<xml>
            <return_code>".$return["return_code"]."</return_code>
            <return_msg>".$return["return_msg"]."</return_msg>
        </xml>";
        return new Response($xml, 200);
    }

    /**
     * @param $option
     * @return string
     */
    public static function getPaySign($option) {
        ksort($option);
        $partnerKey = PackageConfig::$key;
        $buff = "";
        foreach ($option as $k => $v) {
            $buff .= "{$k}={$v}&";
        }
        return strtoupper(md5("{$buff}key={$partnerKey}"));
    }

    /**
     * @param $data
     * @return string
     */
    public static function sign($data) {
        $string = "";
        foreach ($data as $key => $value) {
            if (!$value) continue;
            if ($string) $string .= "&" . $key . "=" . $value;
            else $string = $key . "=" . $value;
        }
        $wx_key = PackageConfig::$key;
        $stringSignTemp = $string . "&key=" . $wx_key;
        return strtoupper(md5($stringSignTemp));
    }

    /**
     * @return string
     */
    public static function getRandStr(){
        $result = "";
        $str = "QWERTYUIOPASDFGHJKLZXVBNMqwertyuioplkjhgfdsamnbvcxz";
        for ($i=0;$i<32;$i++){
            $result .= $str[mt_rand(0,50)];
        }
        return $result;
    }

    /**
     * @param $arr
     * @return string
     */
    private static function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * @return string
     */
    private static function getOrderNumber(){ //生成订单号
        return date("Ymd",time()).time().mt_rand(1000,9999); // 22 位
        // return md5($openid.time().rand(10,99));
    }

    /**
     * @param $xml
     * @return array
     */
    private static function xmlToArray($xml){
        $p = xml_parser_create();
        xml_parse_into_struct($p, $xml, $val, $index);
        xml_parser_free($p);
        $data = [];
        foreach ($index as $key=>$value) {
            if($key == "xml" || $key == "XML") continue;
            $tag = $val[$value[0]]["tag"];
            $value = $val[$value[0]]["value"];
            $data[$tag] = $value;
        }
        return $data;
    }

    /**
     * @param $url
     * @param $xml
     * @return bool|string
     */
    private static function postXmlSSLCurl($url, $xml){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLCERTTYPE,"PEM");
        curl_setopt($ch, CURLOPT_SSLCERT, PackageConfig::$certPath);
        //默认格式为PEM，可以注释
        curl_setopt($ch, CURLOPT_SSLKEYTYPE,"PEM");
        curl_setopt($ch, CURLOPT_SSLKEY, PackageConfig::$keyPath);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        curl_close($ch);
        if($data)return $data;
        else return false;
    }

}