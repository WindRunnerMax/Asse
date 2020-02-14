package com.pay;

import com.utils.PayUtil;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.HashMap;
import java.util.Map;
import javax.servlet.http.HttpServletRequest;

public class PayManager {
    public Map<String,String> qqPay() throws Exception{
        String mchid = PayConfigs.mchid;
        String nonce_str = PayUtil.getRandomStringByLength(16);
        String body = "测试";
        String out_trade_no = "OTS"+ PayUtil.getRandomStringByLength(12); //商户订单号
        String fee_type = "CNY";
        String total_fee = "100"; //自定义货币总额，单位为分
        String spbill_create_ip = ""; // 用户客户端ip
        String trade_type = "JSAPI"; //小程序默认为JSAPI
        String notify_url = "http://www.baidu.com"; //回调地址

        Map<String, String> packageParams = new HashMap<>();
        packageParams.put("mch_id", mchid);
        packageParams.put("nonce_str", nonce_str);
        packageParams.put("body", body);
        packageParams.put("out_trade_no", out_trade_no + ""); //商户订单号
        packageParams.put("total_fee", total_fee + ""); //支付金额，需要转成字符串
        packageParams.put("spbill_create_ip", spbill_create_ip);
        packageParams.put("notify_url", notify_url); //支付成功后的回调地址
        packageParams.put("trade_type", trade_type); //支付方式

        String result = PayUtil.exec(packageParams,PayConfigs.key,PayConfigs.reqAd);
        System.out.println(result);

        // 业务逻辑

        return PayUtil.xmlToMap(result);
    }

    public String acceptPay(HttpServletRequest request) throws Exception{
        BufferedReader br = new BufferedReader(new InputStreamReader(request.getInputStream()));
        String line;
        StringBuilder stringBuilder = new StringBuilder();
        while ((line = br.readLine()) != null) {
            stringBuilder.append(line);
        }
        br.close();
        String notityXml = stringBuilder.toString();
        Map<String,String> acceptParam = PayUtil.xmlToMap(notityXml);
        if (acceptParam.get("trade_state").equals("SUCCESS") && PayUtil.verifySign(acceptParam,PayConfigs.key)){

            // 注意，在QQ服务器收到Accept之前可能会产生多次回调。需要有处理多次回调的代码
            // 业务逻辑

            System.out.println(PayUtil.acceptXML());
        }
        return PayUtil.acceptXML();
    }

    // public static void main(String[] args) {
    //     try {
    //         (new PayManager()).qqPay();
    //     }catch (Exception e){
    //         System.out.println("ERROR");
    //     }
    // }
}
