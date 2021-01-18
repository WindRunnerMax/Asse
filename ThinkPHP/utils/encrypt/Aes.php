<?php
/**
 * Created by Czy
 * Time: 20/01/03 19:22
 * Detail: *
 */

namespace utils\encrypt;
class Aes {
    public static function aesEcrypt($data, $key = 'defaultaeskey', $iv = 'defaultstuffchar') {
        $encryptStr = openssl_encrypt($data, "AES-128-CBC", md5($key), OPENSSL_RAW_DATA, $iv);
        return base64_encode($encryptStr);
    }


    public static function aesDecrypt($data, $key = 'defaultaeskey', $iv = 'defaultstuffchar') {
        return openssl_decrypt(base64_decode($data), 'AES-128-CBC', md5($key), OPENSSL_RAW_DATA, $iv);
    }
}
