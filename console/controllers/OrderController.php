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

    public function actionUpdate()
    {
        $this->run('clear-order');
        $this->run('update-order');
    }

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

    /**
     * 更新会员订单的状态 过期的订单 is_valid 置-1
     */
    public function actionUpdateOrder()
    {
        $orders = OttOrder::find()->where(['<', 'expire_time', time()])->all();

        if ($orders) {
            foreach ($orders as $order) {
                if ($order instanceof OttOrder) {
                    $order->is_valid = '-1';
                    $order->update(false);
                }
            }
        }

    }

}