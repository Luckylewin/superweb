<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/20
 * Time: 17:42
 */

namespace backend\controllers;


use backend\components\MyRedis;

class CacheController extends BaseController
{
    public function actionOtt()
    {
        $redis = MyRedis::init(1);
        $iterator = null;

        $it = NULL;
        while ($keys = $redis->scan($iterator,null,1))
        {
            foreach ($keys as $key) {

            }
        }
    }
}