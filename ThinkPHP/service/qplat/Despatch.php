<?php
/**
 * Created by Czy
 * Time: 20/03/01 22:16
 * Detail: *
 */

namespace service\qplat;

use utils\http\HTTP;
use config\Config;

class Despatch {

    /**
     * @return array
     */
    private static function getHeaders() {
        return [
            "Authorization" => "Bearer " . Config::$CQ_KEY,
            "Content-Type" => "application/json"
        ];
    }

    /**
     * @param $path
     * @param $params
     * @return mixed
     */
    private static function sendHTTP($path, $params) {
        return HTTP::post("http://127.0.0.1:778/$path", json_encode($params), self::getHeaders()) -> json();
    }

    /**
     * @param $user
     * @param $message
     * @return mixed
     */
    public static function privateMsg($user, $message) {
        $params = [
            "user_id" => $user,
            "message" => $message
        ];
        return self::sendHTTP("send_private_msg", $params);
    }

    /**
     * @param $groupId
     * @param $message
     * @param bool $msgParse
     * @return mixed
     */
    public static function groupMsg($groupId, $message, $msgParse = false) {
        $params = [
            "group_id" => $groupId,
            "message" => $message,
            "auto_escape" => $msgParse
        ];
        return self::sendHTTP("send_group_msg", $params);
    }

    /**
     * @param $flag
     * @param bool $approve
     * @param string $remark
     * @return mixed
     */
    public static function friendReq($flag, $approve = true, $remark = "") {
        $params = [
            "flag" => $flag,
            "approve" => $approve,
            "remark" => $remark
        ];
        return self::sendHTTP("set_friend_add_request", $params);
    }

    /**
     * @param $flag
     * @param $type
     * @param bool $approve
     * @param string $reason
     * @return mixed
     */
    public static function groupReq($flag, $type, $approve = true, $reason = "") {
        $params = [
            "flag" => $flag,
            "sub_type" => $type,
            "approve" => $approve,
            "reason" => $reason
        ];
        return self::sendHTTP("set_group_add_request", $params);
    }

    /**
     * @return array
     */
    public static function rebootCQ(){
        $result = self::sendHTTP("set_restart_plugin",["delay" => 2000]);
        return ["reply" => $result];
    }

}