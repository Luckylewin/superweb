<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/27
 * Time: 9:50
 */

namespace console\queues;

use console\jobs\TranslateJob;
use yii\base\BaseObject;


class TranslateQueue extends BaseObject implements \yii\queue\JobInterface
{
    public function execute($queue)
    {
        TranslateJob::typeItem();
    }

}