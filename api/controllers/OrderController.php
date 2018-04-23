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
        \Yii::$app->security->authKeyInfo = 'ZjG5eI88A6L9yLsb';

        $behaviors = parent::behaviors();

        //鉴权
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className()
        ];
        return $behaviors;
    }
}