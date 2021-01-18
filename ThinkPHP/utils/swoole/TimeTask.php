<?php
namespace utils\swoole;

use think\Log;
use utils\singleton\Singleton;

/**
 * @Author Czy
 * @Date 20/09/27
 * @Detail 定时任务类
 */

class TimeTask {

    /**
     * @param $time
     * @param $url
     * @param $post
     * @return array
     */
    public static function loader($time, $url, $post) {
        $instance = Singleton::L(Client::class);
        $client = $instance -> getClient();
        $data = json_encode(["type" => "TimeTask", "time"=>$time, "url"=>$url, "post"=>$post]);
        $client->send($data);
        return ["status" => 1, "id" => $client->recv()];
    }

    /**
     * @param $id
     * @return array
     */
    public static function cancel($id) {
        // Log::write("调用");
        $instance = Singleton::L(Client::class);
        $client = $instance -> getClient();
        $data = json_encode(["type" => "CancelTimeTask", "id" => $id]);
        $client->send($data);
        return ["status" => 1];
    }

}