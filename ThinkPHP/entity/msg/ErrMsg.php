<?php
/**
 * @Author Czy
 * @Date 20/12/21
 * @Detail Created by PHPStorm
 */

namespace entity\msg;


class ErrMsg extends Msg {

    public $msg = null;

    public function __construct($msg = null) {
        $this->status = 0;
        $this->msg = $msg;
    }
}