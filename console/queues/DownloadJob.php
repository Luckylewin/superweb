<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/22
 * Time: 14:01
 */

namespace console\queues;


use yii\base\BaseObject;

/**
 * 下载任务
 * Class DownloadJob
 * @package console\queues
 */
class DownloadJob extends BaseObject implements \yii\queue\JobInterface
{

    /**
     * @var string|array 下载地址
     */
    public $url;

    /**
     * @var string|array 保存路径
     */
    public $file;

    /**
     * 执行方法
     * @var string
     */

    public function execute($queue)
    {
        if (is_array($this->url) && is_array($this->file)) {
            foreach ($this->url as $key => $url) {
                file_put_contents($this->file[$key], file_get_contents($url));
                if ($key != 0 && $key % 2 == 0) {
                    sleep(1);
                }
            }
        } else {
            file_put_contents($this->file, file_get_contents($this->url));
        }
    }
}