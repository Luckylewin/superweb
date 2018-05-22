<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/19
 * Time: 18:41
 */

namespace api\controllers;


use backend\components\MyRedis;
use common\components\AES;
use yii\helpers\Json;
use yii\rest\ActiveController;
use Yii;

class OttController extends ActiveController
{
    public $modelClass = 'common\models\OttClass';
    public $redis;

    /**
     *
     */
    public function actionList()
    {
        $scheme = Yii::$app->request->get('scheme');
        $class = Yii::$app->request->get('class');
        $format = Yii::$app->request->get('format', 'xml');

        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);

        if ($list = $redis->get(self::getKey($class, $scheme, $format,true))) {
            return $list;
        }

        return $this->EncryptOttList(self::getKey($class, $scheme));

    }

    private static function getKey($class, $scheme,$encrypt = false)
    {
        return ($encrypt ? 'ENCRYPT_' : "") . "OTT_LIST_XML_{$class}_{$scheme}";
    }

    /**
     * 加密列表
     * @param $cacheKey
     * @return bool|mixed
     */
    private function EncryptOttList($cacheKey)
    {
        $redis = MyRedis::init(MyRedis::REDIS_PROTOCOL);
        $redis->select(MyRedis::REDIS_PROTOCOL);

        $list = $redis->get($cacheKey);

        if (!isset($list) || $list == false) {
            return false;
        }

        //加密处理
        AES::setKEY(AES::$_KEY);

        $list = preg_replace_callback('/CDATA\[\S+\]]/',function($match) {
            return "CDATA[".AES::encrypt(substr(substr($match[0],6),0,-2))."]]";
        },$list);

        $lists = preg_replace_callback('/url="\s*\S+\s*"/',function($match) {
            $afterSub = trim(substr($match[0],4),'"');
            return 'url="'.AES::encrypt($afterSub).'"';
        },$list);

        $redis->set("ENCRYPT_" . $cacheKey , $lists);
        $redis->set("ENCRYPT_" . $cacheKey . "_updatetime",time());

        return $lists;

    }

}