<?php
/**
 * @author Czy
 * @date 21/05/24
 * @description Created by PHPStorm
 */

namespace system\exception;


class ResponseError extends \RuntimeException {
    private $statusCode;

    public function __construct($statusCode = 200, $message = "System Hint") {
        $this->statusCode = $statusCode;
        parent::__construct($message);
    }

    public function getStatusCode() {
        return $this->statusCode;
    }
}