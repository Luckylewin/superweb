<?php

namespace backend\models;

use common\models\Order;
use Yii;

/**
 * This is the model class for table "ott_order".
 *
 * @property string $oid 自增
 * @property string $uid 用户帐号
 * @property string $genre 类别
 * @property string $order_num 订单id
 * @property int $expire_time 过期时间
 * @property int $is_valid 是否有效
 */
class OttOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'genre', 'order_num', 'expire_time'], 'required'],
            [['expire_time'], 'integer'],
            [['uid', 'order_num'], 'string', 'max' => 32],
            [['genre'], 'string', 'max' => 30],
            [['uid'], 'unique'],
            ['is_valid', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => '用户帐号',
            'genre' => '类别',
            'order_num' => '订单id',
            'expire_time' => '时长',
        ];
    }

    public function getMainOrder()
    {
        return $this->hasOne(Order::className(), ['order_sign' => 'order_num']);
    }

    public function beforeDelete()
    {
        $order = Order::findOne(['order_sign' => $this->order_num]);
        if ($order) {
            $order->delete();
        }
        return true;
    }

}
