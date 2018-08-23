<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/22
 * Time: 18:31
 */

namespace console\queues;


use common\models\MainClass;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class CacheParadeJob extends BaseObject implements JobInterface
{

    public function execute($queue)
    {



    }
}