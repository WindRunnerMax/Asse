package com.utils;

import org.apache.commons.codec.digest.DigestUtils;

import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.util.*;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;


public class PayUtil {

    public static String exec(Map<String, String> map, String key, String gateway) {
        Map<String, String> sortedMap = sortMapByKey(map);
        String sign = getLinkToSign(sortedMap, key);
        String xml = mapToXml(sortedMap, sign);
        String result = PayUtil.httpRequest(gateway, "POST", xml);
        return result;
    }

    public static String getRandomStringByLength(int length) {
        String base = "abcdefghijklmnopqrstuvwxyz0123456789";
        Random random = new Random();
        StringBuilder stringBuilder = new StringBuilder();
        for (int i = 0; i < length; i++) {
            int number = random.nextInt(base.length());
            stringBuilder.append(base.charAt(number));
        }
        return stringBuilder.toString();
    }

    public static Map<String, String> xmlToMap(String strXML) throws Exception {
        Map<String, String> data = new HashMap<>();
        DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
        DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
        InputStream stream = new ByteArrayInputStream(strXML.getBytes(StandardCharsets.UTF_8));
        org.w3c.dom.Document doc = documentBuilder.parse(stream);
        doc.getDocumentElement().normalize();
        NodeList nodeList = doc.getDocumentElement().getChildNodes();
        for (int idx = 0; idx < nodeList.getLength(); ++idx) {
            Node node = nodeList.item(idx);
            if (node.getNodeType() == Node.ELEMENT_NODE) {
                org.w3c.dom.Element element = (org.w3c.dom.Element) node;
                data.put(element.getNodeName(), element.getTextContent());
            }
        }
        return data;
    }

    public static boolean verifySign(Map<String, String> map, String key){
        String sign = map.get("sign");
        map.remove("sign");
        Map<String, String> sortedMap = sortMapByKey(map);
        String xmlSign = getLinkToSign(sortedMap, key);
        return xmlSign.equals(sign);
    }

    public static String acceptXML(){
        return "<xml><return_code>SUCCESS</return_code></xml>";
    }


    private static String sign(String text, String key) {
        text = text + "key=" + key;
//        System.out.println("Sign Url: " + text);
        return DigestUtils.md5Hex(getContentBytes(text)).toUpperCase();
    }


    private static byte[] getContentBytes(String content) {
        return content.getBytes(StandardCharsets.UTF_8);
    }


    private static String getLinkToSign(Map<String, String> map, String payKey) {
        StringBuilder preStr = new StringBuilder();
        for (Map.Entry<String, String> m : map.entrySet()) {
            String key = m.getKey();
            String value = m.getValue();
            preStr.append(key).append("=").append(value).append("&");
        }
        String link = preStr.toString();
        return sign(link, payKey);
    }

    private static String httpRequest(String requestUrl, String requestMethod, String outputStr) {
        StringBuilder stringBuilder = new StringBuilder();
        try {
            URL url = new URL(requestUrl);
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod(requestMethod);
            conn.setDoOutput(true);
            conn.setDoInput(true);
            conn.connect();
            if (null != outputStr) {
                OutputStream os = conn.getOutputStream();
                os.write(outputStr.getBytes(StandardCharsets.UTF_8));
                os.close();
            }
            InputStream is = conn.getInputStream();
            InputStreamReader isr = new InputStreamReader(is, StandardCharsets.UTF_8);
            BufferedReader br = new BufferedReader(isr);
            String line;
            while ((line = br.readLine()) != null) {
                stringBuilder.append(line);
            }
            br.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
        return stringBuilder.toString();
    }


    private static Map<String, String> sortMapByKey(Map<String, String> map) {
        List<String> keys = new ArrayList<>(map.keySet());
        Collections.sort(keys);
        // HashMap底层是数组加链表，会把key的值放在通过哈希算法散列后的对象的数组坐标上，
        // 所以取得的值是按哈希表来取的，所以和放入的顺序无关
        // 保持有序需要用LinkedHashMap
        Map<String, String> m = new LinkedHashMap<>();
        for (String key : keys) {
            m.put(key, map.get(key));
        }
        map.clear();
        return m;
    }

    private static String mapToXml(Map<String, String> map, String sign) {
        StringBuilder stringBuilder = new StringBuilder().append("<xml>");
        for (Map.Entry<String, String> m : map.entrySet()) {
            stringBuilder.append("<").append(m.getKey()).append(">")
                    .append(m.getValue()).append("</").append(m.getKey()).append(">");
        }
        stringBuilder.append("<sign>").append(sign).append("</sign>").append("</xml>");
        return stringBuilder.toString();
    }

}