<?php
/**
 * @Author Czy
 * @Date 20/12/22
 * @Detail Created by PHPStorm
 */
namespace utils\graphic;

use think\Response;

class Captcha {
    private $image = null;
    private $content = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    private $text = "";

    public function __construct(array $config = []) {
        $default = [
            "width" => 150,     // 宽度
            "height" => 50,     // 高度
            "line" => true,    // 直线
            "curve" => false,    // 曲线
            "noise" => 1,       // 噪点背景
            "size" => 4         // 字符数量
        ];
        $config = array_merge($default, $config);
        $config["fontsize"] = intval($config["width"] / floatval($config["size"] * 1.5));
        $config["fontList"] = ["Action.ttf", "ApothecaryFont.ttf", "BigBlock.ttf",
            "Bitsumishi.ttf", "D3Parallelism.ttf", "DeborahFancyDress.ttf"];
        $this->image = $this->draw($config);
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

    public function save($filename, $quality) {
        return imagepng($this->image,$filename,$quality);
    }

    public function thinkOutput() {
        ob_start(); // 打开输出控制缓冲
        imagepng($this -> image); // 输出图像
        $content = ob_get_clean(); // 得到缓冲区内容并清空缓冲
        return new Response($content, 200, ["Content-Length" => strlen($content), "Content-Type" => "image/png"]);
    }

    public function output($quality = 1) {
        header("Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("content-type: image/png");
        imagepng($this->image, null, $quality);
    }

    public function getText() {
        return $this->text;
    }

    public function destroy() {
        imagedestroy($this->image);
    }

    public function __destruct() {
        $this->destroy();
    }

    private function draw($config){
        $image = imagecreatetruecolor($config["width"], $config["height"]);
        list($red, $green, $blue) = $this->getLightColor(); // 浅色
        $backColor = imagecolorallocate($image, $red, $green, $blue);
        imagefill($image,0,0, $backColor);
        $config["noise"] && $this->drawNoise($image, $config);
        if($config["line"]){
            $square = $config["width"] * $config["height"];
            $effects = mt_rand($square/3000, $square/2000);
            for ($e = 0; $e < $effects; $e++) {
                $this->drawLine($image, $config["width"], $config["height"]);
            }
        }
        $config["curve"] && $this->drawSineLine($image, $config);
        $codeNX = 0; // 验证码第N个字符的左边距
        $code = [];
        $textFont = $config["fontList"][mt_rand(0, count($config["fontList"])-1)];
        $textFontLoc = ROOT_PATH . "public/public/static/fonts/${textFont}";
        for ($i = 0; $i < $config["size"]; $i++) {
            $code[$i] = $this->content[mt_rand(0, strlen($this->content) - 1)];
            $codeNX += mt_rand($config["fontsize"] * 1, $config["fontsize"] * 1.3);
            list($red, $green, $blue) = $this->getDeepColor();
            $color = imagecolorallocate($image, $red, $green, $blue);
            if($color === false){
                $color = mt_rand(50,200);
            }
            imagettftext($image, $config["fontsize"], mt_rand(-40, 40), $codeNX,
                $config["height"] / 2, $color, $textFontLoc, $code[$i]);
        }
        $this->text = strtolower(implode("",$code));
        return $image;
    }

    /**
     * 画杂点
     * 往图片上写不同颜色的字母或数字
     * @param $image
     * @param $config
     */
    private function drawNoise($image, $config) {
        $noiseLevel = (int)($config["width"] * 10 / $config["height"]);
        $codeSet = $this -> content;
        for($i = 0; $i < $noiseLevel; $i++){
            list($red,$green,$blue) = $this->getLightColor();
            $noiseColor = imagecolorallocate($image, $red,$green,$blue); //杂点颜色
            for($j = 0; $j < 5; $j++) {
                imagestring($image, 5, mt_rand(-10, $config["width"]),
                    mt_rand(-10, $config["height"]), $codeSet[mt_rand(0, 29)], $noiseColor);
            }
        }
    }

    /**
     *  画曲线
     * @param $image
     * @param $config
     */
    protected function drawSineLine($image, $config) {
        $py = 0;
        // 曲线前部分
        $A = mt_rand(1, $config["height"]/2);                  // 振幅
        $b = mt_rand(-$config["height"]/4, $config["height"]/4);   // Y轴方向偏移量
        $f = mt_rand(-$config["height"]/4, $config["height"]/4);   // X轴方向偏移量
        $T = mt_rand($config["height"], $config["width"]*2);  // 周期
        $w = (2* M_PI)/$T;
        $px1 = 0;  // 曲线横坐标起始位置
        $px2 = mt_rand($config["width"]/2, $config["width"] * 0.8);  // 曲线横坐标结束位置
        $color = imagecolorallocate($image, mt_rand(1, 150), mt_rand(1, 150), mt_rand(1, 150));
        for ($px=$px1; $px<=$px2; $px = $px + 1) {
            if ($w!=0) {
                $py = $A * sin($w*$px + $f)+ $b + $config["height"]/2;  // y = Asin(ωx+φ) + b
                $i = (int) ($config["fontsize"]/5);
                while ($i > 0) {
                    imagesetpixel($image, $px + $i , $py + $i, $color);  // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多
                    $i--;
                }
            }
        }
        // 曲线后部分
        $A = mt_rand(1, $config["height"]/2);                  // 振幅
        $f = mt_rand(-$config["height"]/4, $config["height"]/4);   // X轴方向偏移量
        $T = mt_rand($config["height"], $config["width"]*2);  // 周期
        $w = (2* M_PI)/$T;
        $b = $py - $A * sin($w*$px + $f) - $config["height"]/2;
        $px1 = $px2;
        $px2 = $config["width"];
        for ($px=$px1; $px<=$px2; $px=$px+ 1) {
            if ($w!=0) {
                $py = $A * sin($w*$px + $f)+ $b + $config["height"]/2;  // y = Asin(ωx+φ) + b
                $i = (int) ($config["fontsize"] / 5);
                while ($i > 0) {
                    imagesetpixel($image, $px + $i, $py + $i, $color);
                    $i--;
                }
            }
        }
    }

    /**
     * Draw lines over the image
     * @param $image
     * @param $width
     * @param $height
     * @param null $tcol
     */
    protected function drawLine($image, $width, $height, $tcol = null) {
        if ($tcol === null) {
            $tcol = imagecolorallocate($image, mt_rand(100, 255), mt_rand(100, 255), mt_rand(100, 255));
        }
        if (mt_rand(0, 1)) { // Horizontal
            $Xa = mt_rand(0, $width / 2);
            $Ya = mt_rand(0, $height);
            $Xb = mt_rand($width / 2, $width);
            $Yb = mt_rand(0, $height);
        } else { // Vertical
            $Xa = mt_rand(0, $width);
            $Ya = mt_rand(0, $height / 2);
            $Xb = mt_rand(0, $width);
            $Yb = mt_rand($height / 2, $height);
        }
        imagesetthickness($image, mt_rand(1, 3));
        imageline($image, $Xa, $Ya, $Xb, $Yb, $tcol);
    }

    /**
     * 获取随机颜色
     * @return array
     */
    private function getRandColor() {
        $red = mt_rand(1,254);
        $green = mt_rand(1,254);
        if($red + $green > 400){
            $blue = 0;
        }else{
            $blue = 400 -$green - $red;
        }
        return [$red,$green,$blue];
    }

        /**
         * 获取随机浅色
         * @return array
         */
        private function getLightColor() {
            $colors[0] = 200 + mt_rand(1,55);
            $colors[1] = 200 + mt_rand(1,55);
            $colors[2]= 200 + mt_rand(1,55);
            return $colors;
        }

    /**
     * 获取随机深色
     * @return array
     */
    private function getDeepColor() {
        list($red,$green,$blue) = $this->getRandColor();
        $increase  = 30 + mt_rand(1,254);

        $red = abs(min(255,$red - $increase));
        $green  = abs(min(255,$green - $increase));
        $blue  = abs(min(255,$blue - $increase));

        return [$red,$green,$blue];
    }
}