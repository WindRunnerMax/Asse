<?php
/**
 * Created by Czy
 * Time: 20/01/03 19:22
 * Detail: *
 */

namespace utils\encrypt;
class Aes {
    public static function aesEcrypt($data, $key = 'defaultaeskey', $iv = 'defaultstuffchr') {
        $encrypt_str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($key), $data, MCRYPT_MODE_CBC, $iv);
        return base64_encode($encrypt_str);
    }


    public static function aesDecrypt($data, $key = 'defaultaeskey', $iv = 'defaultstuffchr') {
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5($key), base64_decode($data), MCRYPT_MODE_CBC, $iv);
    }
}