<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/22
 * Time: 15:52
 */

namespace api\controllers;


use api\components\Formatter;
use common\models\BuyRecord;
use common\models\Order;
use common\models\UpgradeRecord;
use common\models\Vod;
use yii\base\InvalidArgumentException;
use yii\filters\auth\QueryParamAuth;

use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\HttpException;

class OrderController extends ActiveController
{
    public $modelClass = 'common\models\Order';

    public $priceList = [
        1 => [
            'type' => 1,
            'title' => '一个月',
            'price' => '4',
        ],

        3 => [
             'type' => 3,
             'title' => '三个月',
             'price' => '12'
        ],

        6 =>  [
            'type' => 6,
            'title' => '六个月',
            'price' => '22'
        ],

        12 => [
            'type' => 12,
            'title' => '一年',
            'price' => '40'
        ]
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //鉴权
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except' => ['price']
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::behaviors();
        unset($actions['index']);
    }

    public function actionIndex()
    {
        $uid = \Yii::$app->user->id;
        return Order::find()
                    ->where(['order_uid' => $uid])
                    ->all();
    }

    public function actionPrice()
    {
        return array_values($this->priceList);
    }

    public function actionUpgrade()
    {
        //价格查找
        $type = \Yii::$app->request->post('type');
        if (!isset($this->priceList[$type])) {
            throw new HttpException(400, "参数有误");
        }

        $price = $this->priceList[$type]['price'];
        $month = \Yii::$app->request->post('type');
        $uid = \Yii::$app->user->getId();
        //生成一个订单
        $order = new Order();
        $order->order_sign = $order->generateOrder();
        $order->order_total = 1;
        $order->order_money = $price;
        $order->order_info = "升级会员";
        $order->order_paytype = 'dokypay';
        $order->order_type = 'vip';
        $order->order_uid = $uid;
        $order->save(false);

        //生成一条升级记录
        $record = new UpgradeRecord();
        $record->user_id = $uid;
        $record->order_id = $order->order_id;
        $record->created_at = time();
        $record->expire_time = strtotime("+$month month");
        $record->save(false);

        return $response = [
            'order_sign' => $order->order_sign,
            'order_money' => $order->order_money,
            'order_total' => $order->order_total,
            'order_addtime' => $order->order_addtime,
            'order_info' => $order->order_info,
            'order_status' => '未支付',
            'url' => "http://" . \Yii::$app->request->hostName . ':8088/index.php?r=site/preview&order=' . $order->order_sign
        ];
    }

    public function actionBuy()
    {
        $vod_id = \Yii::$app->request->post('vod_id');

        //查询价格
        $vod = Vod::findOne($vod_id);
        if (empty($vod)) {
            return Formatter::format(null,Formatter::ITEM_NOT_FOUND, "没有数据");
        }

        $price = $vod->vod_price;

        if ($price <= 0) {
            return Formatter::format(null,Formatter::FREE_OF_CHARGE, "免费");
        }

        //查询此人是否已经购买过
        $record = BuyRecord::findOne(['vod_id' => $vod_id, 'user_id' => \Yii::$app->user->id, 'is_valid' => 1]);
        if ($record) {
            throw new \Exception("已经购买过", Formatter::ALREADY_BUY);
        }

        $uid = \Yii::$app->user->identity->getId();

        //生成一个订单
        $order = new Order();
        $order->order_sign = $order->generateOrder();
        $order->order_uid = $uid;
        $order->order_money = $price;
        $order->order_info = "[" . $vod->vod_name ."]" . "单片观看";
        $order->order_paytype = 'paypal';
        $order->order_type = 'vod';
        $order->save(false);

        //生成一个购买记录
        $record = new BuyRecord();
        $record->user_id = $uid;
        $record->vod_id = $vod_id;
        $record->order_id = $order->order_id;
        $record->save(false);

        return $response = [
            'order_sign' => $order->order_sign,
            'order_money' => $order->order_money,
            'order_total' => $order->order_total,
            'order_addtime' => $order->order_addtime,
            'order_info' => $order->order_info,
            'order_status' => '未支付',
            'url' => "http://" . \Yii::$app->request->hostName . ':8088/index.php?r=site/preview&order=' . $order->order_sign
        ];

    }

}