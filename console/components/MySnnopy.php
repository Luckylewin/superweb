<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 16:47
 */

namespace console\components;


use Snoopy\Snoopy;

class MySnnopy
{
    public static function init()
    {
        $snnopy = new Snoopy();
        $snnopy->agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36';
        $snnopy->referer = "www.google.com/";
        return $snnopy;
    }
}