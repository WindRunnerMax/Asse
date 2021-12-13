<?php
/**
 * @Author Czy
 * @Date 20/12/21
 * @Detail Created by PHPStorm
 */

namespace entity\msg;


class SucMsg extends Msg {

    public $info = null;

    public function __construct($info = null, $args = []) {
        parent::__construct(null, $args);
        $this->status = 1;
        $this->info = $info;
    }
}