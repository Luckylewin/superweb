<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "iptv_upgrade_record".
 *
 * @property int $id
 * @property int $user_id
 * @property int $expire_time
 * @property int $order_id
 * @property int $is_deal
 * @property int $created_at
 */
class UpgradeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_upgrade_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'expire_time', 'order_id'], 'required'],
            [['id', 'user_id', 'expire_time', 'order_id', 'created_at'], 'integer'],
            [['is_deal'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'expire_time' => 'Expire Time',
            'order_id' => 'Order ID',
            'is_deal' => 'Is Deal',
            'created_at' => 'Created At',
        ];
    }
}
