<?php
/**
 * @Author Czy
 * @Date 20/08/05
 * @Detail Created by PHPStorm
 */

namespace utils\http;


use utils\params\P;

class Response {
    private $reqHeaders = [];
    private $resHeaders = [];
    private $resText = "";

    public function __construct($reqHeaders, $resHeaders, $resText) {
        $this -> reqHeaders = $reqHeaders;
        $this -> resHeaders = $resHeaders;
        $this -> resText = $resText;
    }

    public function headers($key, $req = false) {
        $key = strtolower($key);
        if($req) return P::safeKey($this->reqHeaders, $key);
        return P::safeKey($this->resHeaders, $key);
    }

    public function getAllHeaders($req = false) {
        if($req) return $this->reqHeaders;
        return $this->resHeaders;
    }

    public function text(){
        return $this -> resText;
    }

    public function json(){
        return json_decode($this -> resText, true);
    }
}