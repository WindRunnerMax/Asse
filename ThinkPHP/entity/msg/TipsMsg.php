<?php
/**
 * @Author Czy
 * @Date 20/12/21
 * @Detail Created by PHPStorm
 */

namespace entity\msg;


class TipsMsg extends Msg {

    public $msg = null;

    public function __construct($msg) {
        $this->status = -1;
        $this->msg = $msg;
    }

}