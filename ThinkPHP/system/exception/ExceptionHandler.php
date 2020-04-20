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


class ExceptionHandler extends Handle {
    public function render(Exception $e) {
        if (!App::$debug) return json(["status" => 0,"msg" => "System Hint"],200);
        return parent::render($e); // 交由框架处理
    }
}