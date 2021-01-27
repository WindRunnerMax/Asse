<?php
/**
 * @Author Czy
 * @Date 20/07/10
 * @Detail Created by PHPStorm
 */

namespace service\wx\mplat;


class ToXML {

    /**
     * @param $msg
     * @param $content
     * @return string
     */
    public static function text($msg, $content) {
        return "<xml>
                  <ToUserName><![CDATA[" . $msg->FromUserName . "]]></ToUserName>
                  <FromUserName><![CDATA[" . $msg->ToUserName . "]]></FromUserName>
                  <CreateTime>" . $msg->CreateTime . "</CreateTime>
                  <MsgType><![CDATA[text]]></MsgType>
                  <Content><![CDATA[" . $content . "]]></Content>
                </xml>";
    }

    /**
     * @param $msg
     * @param $title
     * @param $content
     * @param $imgUrl
     * @param $url
     * @return string
     */
    public static function imgText($msg, $title, $content, $imgUrl, $url) {
        return "<xml>
                  <ToUserName><![CDATA[" . $msg->FromUserName . "]]></ToUserName>
                  <FromUserName><![CDATA[" . $msg->ToUserName . "]]></FromUserName>
                  <CreateTime>" . $msg->CreateTime . "</CreateTime>
                  <MsgType><![CDATA[news]]></MsgType>
                  <ArticleCount>1</ArticleCount>
                  <Articles>
                    <item>
                      <Title><![CDATA[" . $title . "]]></Title>
                      <Description><![CDATA[" . $content . "]]></Description>
                      <PicUrl><![CDATA[" . $imgUrl . "]]></PicUrl>
                      <Url><![CDATA[" . $url . "]]></Url>
                    </item>
                  </Articles>
                </xml>";
    }

    /**
     * @param $msg
     * @param $id
     * @return string
     */
    public static function img($msg, $id) {
        return "<xml>
                  <ToUserName><![CDATA[" . $msg->FromUserName . "]]></ToUserName>
                  <FromUserName><![CDATA[" . $msg->ToUserName . "]]></FromUserName>
                  <CreateTime>" . $msg->CreateTime . "</CreateTime>
                  <MsgType><![CDATA[image]]></MsgType>
                      <Image>
                        <MediaId><![CDATA[" . $id . "]]></MediaId>
                      </Image>
                </xml>";
    }
}