<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "firmware_class".
 *
 * @property int $id
 * @property string $name 产品名称
 * @property string $is_use
 * @property int $order_id 订单id
 */
class FirmwareClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'firmware_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['is_use'], 'string', 'max' => 1],
            ['is_use', 'default', 'value' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('backend', 'Name'),
            'is_use' => Yii::t('backend', 'Is Available'),
            'order_id' => Yii::t('backend', 'Binding order number'),
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(DvbOrder::className(), ['id' => 'order_id']);
    }

    public static function getDropDownList()
    {
        $order_id = self::find()->select('order_id')->asArray()->all();
        if ($order_id) {
            $order_id = ArrayHelper::getColumn($order_id, 'order_id');
        } else {
            $order_id = [];
        }

        //查找可用的订单号数据
        $orders = DvbOrder::find()->where(['not in', 'id', $order_id ])->select('id,order_num')->asArray()->all();
        if ($orders) {
            $orders = ArrayHelper::map($orders, 'id', 'order_num');
        } else {
            $orders = [];
        }

        return $orders;
    }

}
