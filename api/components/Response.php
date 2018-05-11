<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/11
 * Time: 10:52
 */

namespace api\components;


class Response
{
    public static function error($code)
    {
        return Formatter::format(null, $code, Formatter::getMessage($code));
    }
}