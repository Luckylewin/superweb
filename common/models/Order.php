<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "iptv_order".
 *
 * @property int $order_id
 * @property string $order_sign 订单日期
 * @property int $order_status 订单状态
 * @property int $order_uid 用户ID
 * @property int $order_total 订单数量
 * @property string $order_money 订单金额
 * @property int $order_ispay 是否支付
 * @property int $order_addtime 下单时间
 * @property int $order_paytime 支付时间
 * @property int $order_confirmtime 订单确认时间
 * @property string $order_info 订单信息
 * @property string $order_paytype 支付类型
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_sign', 'order_total', 'order_addtime', 'order_paytime', 'order_confirmtime', 'order_info', 'order_paytype'], 'required'],
            [['order_uid', 'order_total', 'order_addtime', 'order_paytime', 'order_confirmtime'], 'integer'],
            [['order_money'], 'number'],
            [['order_info'], 'string'],
            [['order_sign'], 'string', 'max' => 32],
            [['order_status', 'order_ispay'], 'string', 'max' => 1],
            [['order_paytype'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_sign' => '订单日期',
            'order_status' => '订单状态',
            'order_uid' => '用户ID',
            'order_total' => '订单数量',
            'order_money' => '订单金额',
            'order_ispay' => '是否支付',
            'order_addtime' => '下单时间',
            'order_paytime' => '支付时间',
            'order_confirmtime' => '订单确认时间',
            'order_info' => '订单信息',
            'order_paytype' => '支付类型',
        ];
    }
}
