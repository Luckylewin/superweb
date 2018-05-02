<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/22
 * Time: 15:52
 */

namespace api\controllers;


use api\components\Formatter;
use common\models\Order;
use common\models\Vod;
use yii\filters\auth\QueryParamAuth;

use yii\helpers\Url;
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

        return $behaviors;
    }

    public function actionUpgrade()
    {
        //价格暂时硬编码
        $price = 10;
        $month = \Yii::$app->request->post('month');
        
        $order = new Order();
        $order->order_sign = $order->generateOrder();
        $order->order_total = 1;
        $order->order_money = $price;
        $order->order_info = "升级会员";
        $order->order_paytype = 'paypal';
        $order->save(false);

        return $response = [
            'order_sign' => $order->order_sign,
            'order_money' => $order->order_money,
            'order_total' => $order->order_total,
            'order_addtime' => $order->order_addtime,
            'order_info' => $order->order_info,
            'order_status' => '未支付',
            'url' => "http://" . \Yii::$app->request->hostName . ':10080/index.php?r=pay/create&order=' . $order->order_sign
        ];
    }

    public function actionBuy()
    {
        $vod_id = \Yii::$app->request->post('vod_id');
        //查询价格
        $vod = Vod::findOne($vod_id);
        if (empty($vod)) {
            throw new \Exception("没有数据", Formatter::ITEM_NOT_FOUND);
        }

        $price = $vod->vod_price;
        if ($price <= 0) {
            throw new \Exception("免费", Formatter::FREE_OF_CHARGE);
        }
        //生成一个订单
        $order = new Order();
        $order->order_sign = $order->generateOrder();
        $order->order_total = 1;
        $order->order_money = $price;
        $order->order_info = "[" . $vod->vod_name ."]" . "单片观看";
        $order->order_paytype = 'paypal';
        $order->save(false);

        return $response = [
            'order_sign' => $order->order_sign,
            'order_money' => $order->order_money,
            'order_total' => $order->order_total,
            'order_addtime' => $order->order_addtime,
            'order_info' => $order->order_info,
            'order_status' => '未支付',
            'url' => "http://" . \Yii::$app->request->hostName . ':10080/index.php?r=pay/create&order=' . $order->order_sign
        ];

    }

}