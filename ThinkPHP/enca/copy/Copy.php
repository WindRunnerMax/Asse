<?php
namespace enca\copy;

/**
 * 浅拷贝与深拷贝
 */
class Copy
{
    /**
     * 浅拷贝
     * 数组是通过引用传递的，除非它在你调用的方法/函数中被修改。
     * 如果尝试在方法/函数中修改数组，则首先创建它的副本，然后仅修改副本。
     */
     public static function sCopy(&...$arguments){
         $aLength = count($arguments);
         $options = &$arguments[0];
         for ($i = 1; $i < $aLength ; ++$i) {
             foreach ($arguments[$i] as $key => $value) {
                 $options[$key] = $value;
             }
         }
         return $options;
     }
//    public static function sCopy(&$a,&$b){
//        foreach ($b as $key => $value) {
//            $a[$key] = $value;
//        }
//        return $a;
//    }

     /**
      * 深拷贝
      */
     public static function dCopy(...$arguments){
         $options = [];
         foreach ($arguments as $argument) {
             foreach ($argument as $key => $value) {
                 if(gettype($value) === 'array') $options[$key] = self::dCopy($value);
                 else $options[$key] = $value;
             }
         }
         return $options;
     }

}