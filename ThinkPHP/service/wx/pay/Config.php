<?php
/**
 * Created by Czy
 * Time: 20/10/25 16:00
 * Detail: *
 */

namespace service\wx\pay;

class Config{

    static $mchId = "";
    static $key = "";
    static $submitUrl = "https://api.mch.weixin.qq.com/pay/unifiedorder";
    static $notify = "https://shsv.touchczy.top/pay/accept";
    static $keyPath = "";
    static $certPath = "";
    static $refundUrl = "https://api.mch.weixin.qq.com/secapi/pay/refund";
}