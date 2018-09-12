<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/9/12
 * Time: 11:01
 */

namespace console\jobs;


use backend\components\MyRedis;
use backend\models\Mac;

class SyncOnlineStateJob
{
    public static function start()
    {
        $redis = MyRedis::init(MyRedis::REDIS_DEVICE_STATUS);
        Mac::updateAll(['is_online' => 0], ['is_online' => 1]);

        foreach (Mac::find()->select('MAC')->asArray()->each() as $mac) {
            $logintime =  $redis->hget($mac['MAC'], 'logintime');
            if ($logintime && time() - strtotime($logintime) <= 6 * 3600 ) {
                Mac::updateAll(['is_online' => 1, 'logintime' => date('Y-m-d H:i:s')], ['MAC' => $mac['MAC']]);
            }
        }

        echo "任务执行结束";
    }
}