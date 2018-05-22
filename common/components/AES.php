<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/16
 * Time: 9:39
 */

namespace common\components;

/**
 * AES加密工具类
 * 使用方法
 *
 * $encryptedText = AES::encrypt('http://www.baidu.com');
 * AES::decrypt($encryptedText);
 *
 * Class MyEncrypt
 * @package Applications\Karaoke\components\encrypt
 */
class AES
{
    public static $_KEY = "ZjG5eI88A6L9yLsb";

    public static $_IV = "7MgKWKZPzAwN5kCc";
    //密钥
    public static $KEY = "ZjG5eI88A6L9yLsb";
    //偏移量
    public static $IV = "7MgKWKZPzAwN5kCc";

    /**
     * 设置偏移量
     * @param $key
     */
    public static function setKEY($key)
    {
        self::$KEY = $key;
    }

    /**
     * @param $iv
     */
    public static function setIV($iv)
    {
        self::$IV = $iv;
    }

    /**
     * 加密
     * @param $str
     * @param string $iv
     * @param string $key
     * @return string
     */
    public static function encrypt($str, $iv=null, $key=null )
    {
        if (is_null($iv)) {
            $iv = self::$IV;
        }
        if (is_null($key)) {
            $key = self::$KEY;
        }
        if (PHP_VERSION >= 7) {
            $base = openssl_encrypt($str,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
        } else {
            $base = (mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,self::addPkcs7Padding($str,16) , MCRYPT_MODE_CBC, $iv));
        }

        return self::strToHex($base);
    }

    /**
     * 解密
     * @param $encryptedText
     * @param string $iv
     * @param string $key
     * @return String
     */
    public static function decrypt($encryptedText,  $iv=null, $key=null)
    {
        if (is_null($iv)) {
            $iv = self::$IV;
        }
        if (is_null($key)) {
            $key = self::$KEY;
        }

        if (PHP_VERSION > 7) {
            return openssl_decrypt(self::hexToStr($encryptedText),'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
        } else {
            $str = self::hexToStr($encryptedText);
            return self::stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $iv));
        }
    }

    /**128解密
     * @param $encryptedText
     * @param string $iv
     * @param string $key
     * @return String
     */
    public static function aes128cbcDecrypt($encryptedText, $iv=null, $key=null)
    {
        if (is_null($iv)) {
            $iv = self::$IV;
        }
        if (is_null($key)) {
            $key = self::$KEY;
        }
        $encryptedText =base64_decode($encryptedText);
        return self::stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryptedText, MCRYPT_MODE_CBC, $iv));
    }

    /**
     * 加密然后base64转码
     * @param String 明文
     * @param 加密的初始向量（IV的长度必须和Blocksize一样， 且加密和解密一定要用相同的IV）
     * @param $key 密钥
     * @return string
     */
    public function aes256cbcEncrypt($str, $iv, $key )
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, self::addPkcs7Padding($str) , MCRYPT_MODE_CBC, $iv));
    }

    /**
     * 256解密
     * @param String $encryptedText 二进制的密文
     * @param String $iv 加密时候的IV
     * @param String $key 密钥
     * @return String
     */
    public function aes256cbcDecrypt($encryptedText, $iv, $key)
    {
        $encryptedText =base64_decode($encryptedText);
        return self::stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encryptedText, MCRYPT_MODE_CBC, $iv));
    }

    /**
     * pkcs7补码
     * @param string $string  明文
     * @param int $blocksize Blocksize , 以 byte 为单位
     * @return String
     */
    public static function addPkcs7Padding($string, $blocksize = 32)
    {
        $len = strlen($string); //取得字符串长度
        $pad = $blocksize - ($len % $blocksize); //取得补码的长度
        $string .= str_repeat(chr($pad), $pad); //用ASCII码为补码长度的字符， 补足最后一段
        return $string;
    }


    /**
     * 除去pkcs7 padding
     * @param String 解密后的结果
     * @return String
     */
    private static function stripPkcs7Padding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);

        if(preg_match("/$slastc{".$slast."}/", $string)){
            $string = substr($string, 0, strlen($string)-$slast);
            return $string;
        } else {
            return false;
        }
    }

    //十六进制转字符串
    public static function hexToStr($hex)
    {
        $string="";
        for($i=0;$i<strlen($hex)-1;$i+=2)
            $string.=chr(hexdec($hex[$i].$hex[$i+1]));
        return  $string;
    }

    //字符串转十六进制
    public static function strToHex($string)
    {
        $hex="";
        for($i=0;$i<strlen($string);$i++)
        {
            $tmp = dechex(ord($string[$i]));
            $hex.= strlen($tmp) == 1 ? "0".$tmp : $tmp;
        }
        $hex=strtoupper($hex);
        return $hex;
    }


}
