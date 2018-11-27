<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/8
 * Time: 18:17
 */

namespace backend\components;

use Yii;
use yii\redis\Connection;

class MyRedis
{
    private static $redis;

    const REDIS_PARADE_CONTENT	      =	0;
    const REDIS_DEVICE_STATUS	      =	1;
    const REDIS_EPG     =	2;
    const REDIS_SERVER_ID_STATUS      =	3;
    const REDIS_PROTOCOL          	  =	4;
    const REDIS_VOD_ALBUM          	  =	5;
    const REDIS_VOD_INFO          	  =	6;
    const REDIS_VOD_DOWNLOAD          = 7;
    const REDIS_OTT_URL               =	8;
    const REDIS_CHINA_IP              = 11;
    const REDIS_AUTH_TOKEN            = 12;
    const REDIS_ADVER_DATA            = 13;
    const REDIS_DEVICE_ONLINE_STATUS  = 15;

    /**
     *
     * @param int $database
     * @return Connection
     */
    public static function init($database = 0)
    {
        if (self::$redis) {
            return self::$redis;
        }

        $redis = Yii::$app->redis;
        $redis->select($database);

        return self::$redis = $redis;
    }

}