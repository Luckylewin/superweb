<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/22
 * Time: 15:52
 */

namespace api\controllers;


use yii\filters\auth\QueryParamAuth;
use yii\filters\RateLimiter;
use yii\rest\ActiveController;

class OrderController extends ActiveController
{
    public $modelClass = 'common\models\Order';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //鉴权
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className()
        ];

        //如果超出每天请求的次数的限制 将会抛出异常
        /*$behaviors['rateLimiter'] = [
            'class' => RateLimiter::className(),
            'enableRateLimitHeaders' => true
        ];*/

        return $behaviors;
    }
}