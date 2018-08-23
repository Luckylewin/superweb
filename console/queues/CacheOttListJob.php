<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/23
 * Time: 10:33
 */

namespace console\queues;


use backend\models\Cache;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class CacheOttListJob extends BaseObject implements JobInterface
{
    /**
     * @var integer|array 主要分类ID
     */
    public $id;

    public function execute($queue)
    {
        if (is_array($this->id)) {
            foreach ($this->id as $id) {
                echo "生成缓存中";
                $this->_generateCache($id);
            }
        } else {
            $this->_generateCache($this->id);
        }
    }

    private function _generateCache($id)
    {
        $cache = new Cache();

        $cache->createOttCache($id, Cache::$JSON);
        $cache->createOttCache($id, Cache::$XML);
    }
}