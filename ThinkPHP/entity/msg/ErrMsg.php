<?php
/**
 * @Author Czy
 * @Date 20/12/21
 * @Detail Created by PHPStorm
 */

namespace entity\msg;


class ErrMsg extends Msg {

    public function __construct($msg = null, $args = []) {
        parent::__construct($msg, $args);
        $this->status = 0;
    }
}