<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/16
 * Time: 11:40
 */

namespace console\controllers;


use backend\models\OttOrder;
use common\models\Order;
use yii\console\Controller;

class OrderController extends Controller
{
    /**
     * 删除过期没支付的订单
     * @return bool
     */
    public function actionClearOrder()
    {
        $orders = Order::find()->where(['order_ispay' => 0])->andWhere(['<','order_addtime', time() - 86400])->all();

        if (!empty($orders)) {
            foreach ($orders as $order) {
                 $ottOrder = OttOrder::find()->where(['order_num' => $order->order_sign])->one();
                 if ($ottOrder) {
                    $ottOrder->delete();
                 }
                 $order->delete();
            }
        }

        return true;
    }
}