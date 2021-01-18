<?php
/**
 * @Author Czy
 * @Date 20/07/10
 * @Detail Created by PHPStorm
 */

namespace utils\format;


class ToArray {

    /**
     * @param $xml
     * @return mixed
     */
    function xmlToArray($xml){
        libxml_disable_entity_loader(true); // 禁止引用外部xml实体
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }

    function jsonToArray($json){
        return json_decode($json, true);
    }
}