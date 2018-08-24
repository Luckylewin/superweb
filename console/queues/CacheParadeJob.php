<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/22
 * Time: 18:31
 */

namespace console\queues;



use console\jobs\ParadeJob;
use yii\base\BaseObject;
use yii\queue\JobInterface;


/**
 * 生成中间表缓存
 * Class CacheParadeJob
 * @package console\queues
 */
class CacheParadeJob extends BaseObject implements JobInterface
{

    public function execute($queue)
    {
        ParadeJob::generateCache();
    }


}