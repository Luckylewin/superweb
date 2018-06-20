<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dvb_order".
 *
 * @property int $id
 * @property string $product_name 订单名称
 * @property string $order_num 订单号
 * @property string $order_date 订单日期
 * @property string $order_count 订单数量
 * @property int $client_id 客户id
 */
class DvbOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dvb_order';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'order_date',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['order_date']
                ],
                'value' => date('Y-m-d')
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_num'], 'required'],
            [['order_date'], 'safe'],
            [['client_id'], 'integer'],
            [['product_name', 'order_count'], 'string', 'max' => 20],
            [['order_num'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_name' => '产品名称',
            'order_num' => '订单号',
            'order_date' => '订单日期',
            'order_count' => '订单数量',
            'client_id' => '客户ID',
        ];
    }

    public function getClient()
    {
        return $this->hasOne(SysClient::className(), ['id' => 'client_id']);
    }
}
