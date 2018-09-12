<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/9/12
 * Time: 13:19
 */

namespace console\queues;


use console\jobs\SyncOnlineStateJob;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SyncJob extends BaseObject implements JobInterface
{
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        SyncOnlineStateJob::start();
    }

}