<?php

namespace common\models;

use Yii;
use backend\models\Mac;

/**
 * This is the model class for table "iptv_order".
 *
 * @property int $order_id
 * @property string $order_sign 订单号
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
 * @property string $order_type 物品类型
 * @property string $del_flag 软删除标志
 */
class Order extends \yii\db\ActiveRecord
{
    const SOFT_DEL = 1;

    const PAID = 1;
    const UNPAID = 0;

    public static $payType = [
        'alipay' => 'Alipay',
        'wxpay' => 'WeChat payment',
        'paypal' => 'Paypal',
        'dokypay'=> 'DokyPay'
    ];


    public static function getPayStatus($status = null)
    {
        $payStatus = [
            self::PAID => Yii::t('backend', 'Paid'),
            self::UNPAID => Yii::t('backend', 'Unpaid'),
        ];

        if (is_null($status)) {
            return $payStatus;
        }

        return $payStatus[$status];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_order';
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($this->isNewRecord) {
            $this->order_total = 1;
            $this->order_addtime = time();
            $this->order_ispay = 0;
            $this->order_status = 0;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_sign'], 'required'],
            [['order_uid', 'order_total', 'order_addtime', 'order_paytime', 'order_confirmtime'], 'integer'],
            [['order_money'], 'number'],
            [['order_info','del_flag'], 'string'],
            [['order_sign'], 'string', 'max' => 32],
            [['order_status', 'order_ispay'], 'string', 'max' => 1],
            [['order_paytype'], 'string', 'max' => 64],
            ['order_type', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_sign' => Yii::t('backend', 'Order Date'),
            'order_status' => Yii::t('backend', 'Order Status'),
            'order_uid' => Yii::t('backend', 'User ID'),
            'order_total' => Yii::t('backend', 'Order Quantity'),
            'order_money' => Yii::t('backend', 'Order Amount'),
            'order_ispay' => Yii::t('backend', 'Payment Status'),
            'order_addtime' => Yii::t('backend', 'Order Time'),
            'order_paytime' => Yii::t('backend', 'Pay Time'),
            'order_confirmtime' => Yii::t('backend', 'Confirmation Time'),
            'order_info' => Yii::t('backend', 'Order information'),
            'order_paytype' => Yii::t('backend', 'Pay Type'),
            'user.username' => Yii::t('backend', 'username'),
            'paystatus' => Yii::t('backend', 'Order Status')
        ];
    }

    public function fields()
    {
        return [
            //'order_id',
            'order_sign',
            'order_status',
            'order_uid' => function($model) {
                $user = User::find()->select('username')->where(['id' => $model->order_uid])->one();
                if ($user) {
                    return $user->username;
                }
                $user = Mac::find()->select('MAC')->where(['id' => $model->order_uid])->one();
                if ($user) {
                    return $user->MAC;
                }
                return $model->order_uid;
            },
            'order_total',
            'order_money',
            'order_ispay',
            'order_addtime'=> function($model) {
                return date('Y-m-d H:i:s', $model->order_addtime);
            },
            'order_paytime' => function($model) {
                return date('Y-m-d H:i:s', $model->order_paytime);
            },
            'order_confirmtime' => function($model) {
                return date('Y-m-d H:i:s', $model->order_confirmtime);
            },
            'order_info',
            'order_paytype' => function($model) {
                return self::$payType[$model->order_paytype];
            }

        ];
    }

    /**
     *
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'order_uid']);
    }

    public function getPayType()
    {
        return self::$payType[$this->order_paytype] ?? '未定义';
    }

    /**
     * 产生订单号
     * @return string
     */
    public function generateOrder()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        return $orderSn = $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    }

    public static function getTotal($start, $end)
    {
       return self::find()
                    ->where(['>=', 'order_addtime', $start])
                    ->andFilterWhere(['<', 'order_addtime', $end])
                    ->andWhere(['order_ispay' => self::PAID])
                    ->count();
    }

    public static function getSumMoney($start, $end)
    {
        $sum =  self::find()
                    ->select('order_money')
                    ->where(['>=', 'order_addtime', $start])
                    ->andFilterWhere(['<', 'order_addtime', $end])
                    ->andWhere(['order_ispay' => self::PAID])
                    ->sum('order_money');

        return is_null($sum) ? 0 : $sum;
    }

}
