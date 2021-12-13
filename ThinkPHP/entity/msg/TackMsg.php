<?php
/**
 * @Author Czy
 * @Date 20/12/21
 * @Detail Created by PHPStorm
 */

namespace entity\msg;


class TackMsg extends Msg {

    public function __construct($status, $args = []) {
        parent::__construct(null, $args);
        $this->status = $status;
    }
}