<?php
/**
 * @Author Czy
 * @Date 20/04/06
 * @Detail Created by PHPStorm
 */

namespace system\exception;
use Exception;
use think\App;
use think\exception\Handle;
use think\exception\RouteNotFoundException;


class ExceptionHandler extends Handle {

    protected $ignoreReport = [
        "\\think\\exception\\HttpException",
        "\\system\\exception\\ResponseError",
    ];

    public function render(Exception $e) {
        if(!App::$debug) {
            if($e instanceof RouteNotFoundException) return json(["status" => -1,"msg" => "Router Error"], 200);
            else if($e instanceof ResponseError) return json(["status" => -1,"msg" => $e->getMessage()], 200);
            return json(["status" => 0,"msg" => "System Hint"], 200);
        }
        return parent::render($e); // 交由框架处理
    }
}