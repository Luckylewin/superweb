<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "iptv_renew".
 *
 * @property int $id
 * @property string $mac 续费用户
 * @property int $date 续费日期
 * @property string $renew_period 续费时长
 * @property string $expire_time  预计过期时间
 * @property string $card_num 卡号
 * @property string $renew_operator '操作用户'
 */
class RenewLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_renew';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mac', 'date', 'renew_period', 'card_num'], 'required'],
            [['date'], 'integer'],
            [['mac'], 'string', 'max' => 36],
            [['renew_period'], 'string', 'max' => 30],
            [['card_num'], 'string', 'max' => 20],
            [['renew_operator'], 'string', 'max' => 1],
            ['expire_time','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mac' => '续费用户',
            'date' => '续费日期',
            'renew_period' => '续费时长',
            'card_num' => '卡号',
            'renew_operator' => '操作用户',
            'expire_time' => '过期时间'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date = time();
        }
        return true;
    }

}
