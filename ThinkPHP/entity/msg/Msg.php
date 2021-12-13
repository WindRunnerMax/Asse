<?php
/**
 * @Author Czy
 * @Date 20/12/21
 * @Detail Created by PHPStorm
 */

namespace entity\msg;

class Msg {

    public $status = 1;
    public $msg = null;

    public function __construct($msg = null, $args = []) {
        $this->msg = $msg;
        foreach ($args as $key => $value){
            $this->$key = $value;
        }
    }

    public static function instance($msg = null, $args = []) {
        return new static($msg, $args);
    }
}