<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "iptv_recharge_card".
 *
 * @property string $card_num 卡号
 * @property string $card_secret 卡密
 * @property string $card_contracttime 卡时长
 * @property string $is_valid 是否有效
 * @property string $is_del 是否删除
 * @property string $is_use 是否使用
 * @property string $create_time '生成时间'
 * @property int $batch
 */
class RechargeCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_recharge_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_num', 'card_secret', 'card_contracttime', 'create_time', 'batch'], 'required'],
            [['create_time'], 'safe'],
            [['batch'], 'integer'],
            [['card_num', 'card_secret'], 'string', 'max' => 16],
            [['card_contracttime'], 'string', 'max' => 10],
            [['is_valid', 'is_use'], 'string', 'max' => 1],
            [['is_del'], 'string', 'max' => 255],
            [['card_num'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'card_num' => Yii::t('backend', 'Renewal card number'),
            'card_secret' => Yii::t('backend', 'Card key'),
            'card_contracttime' => Yii::t('backend', 'Card duration'),
            'is_valid' => Yii::t('backend', 'Valid Status'),
            'is_del' => Yii::t('backend', 'Soft delete'),
            'is_use' => Yii::t('backend', 'Status of use'),
            'create_time' => Yii::t('backend', 'Created Time'),
            'batch' => Yii::t('backend', 'Batch'),
        ];
    }
}
