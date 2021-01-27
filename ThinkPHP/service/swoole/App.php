<?php

/**
 * @Author Czy
 * @Date 20/09/27
 * @Detail Swoole 启动类
 */

namespace service\swoole;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use \Swoole\Timer;
use \Swoole\Server;
use utils\http\HTTP;


class App extends Command {

    private $server = null;

    protected function configure(){
        $this->setName("swoole")->setDescription("swoole");
    }

    private function writeLog($e, $data){
        $path = ROOT_PATH."runtime".DS."log".DS."swoole_err.log";
        if(file_exists($path)) $log = fopen($path, "a");
        else $log = fopen($path, "w");
        $time = date("Y-m-d H:i:s",time());
        fwrite($log, $time."\n".$data."\n".$e."\n\n");
        fclose($log);
    }

    protected function execute(Input $input, Output $output){
        $this->server = new Server("127.0.0.1", 9501);
        $this->server->on("receive", function($server, $fd, $from_id, $data) {
            try{
                $parsed = json_decode($data, true);
                if(!isset($parsed["type"])) return 0;
                if($parsed["type"] === "TimeTask"){
                    $tid = Timer::after($parsed["time"], function() use ($parsed){
                        try{
                            HTTP::post($parsed["url"], $parsed["post"]);
                        }catch (\Exception $e){
                            $server -> send($fd, 0);
                            $this -> writeLog($e, $data);
                        }
                    });
                    $server -> send($fd, $tid);
                }else if($parsed["type"] === "CancelTimeTask"){
                    $success = Timer::clear($parsed["id"]);
                    $server -> send($fd, $success);
                }
            }catch (\Exception $e){
                $server -> send($fd, 0);
                $this -> writeLog($e, $data);
            }
        });
        $this->server->start();
    }

}