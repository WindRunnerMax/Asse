<?php
/**
 * Created by Czy
 * Time: 19/12/22 23:00
 * Detail: Config
 */

namespace config;

use utils\params\P;

class Config{

    public static $APPID = "";
    public static $SECRET = "";
    public static $CQ_KEY = "";

    /**
     * @return String
     */
    public static function getHOST() {
        return P::safeKey($_SERVER, "REQUEST_SCHEME")."://".P::safeKey($_SERVER, "SERVER_NAME")."/";
    }

}