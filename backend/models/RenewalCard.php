<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_renewal_card".
 *
 * @property string $card_num 卡号
 * @property string $card_secret 卡密
 * @property string $card_contracttime 续费时长
 * @property string $is_del 是否被删除
 * @property string $is_valid 是否已使用
 * @property int $created_time 创建时间
 * @property int $updated_time 更新时间
 * @property int $batch 批次
 * @property string $who_use 使用的人
 */
class RenewalCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_renewal_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_num', 'card_secret'], 'required'],
            [['created_time', 'updated_time', 'batch'], 'integer'],
            [['card_num', 'card_secret'], 'string', 'max' => 16],
            [['card_contracttime'], 'string', 'max' => 10],
            [['is_del', 'is_valid'], 'string', 'max' => 1],
            [['who_use'], 'string', 'max' => 30],
            [['card_num'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'card_num' => '卡号',
            'card_secret' => '卡密',
            'card_contracttime' => '续费时长',
            'is_del' => '是否被删除',
            'is_valid' => '是否已使用',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
            'batch' => '批次',
            'who_use' => '使用的人',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_time',
                'updatedAtAttribute' => 'updated_time',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_time', 'updated_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_time',
                ],
                'value' => time()
            ]
        ];
    }

}
