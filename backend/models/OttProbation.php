<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ott_probation".
 *
 * @property string $mac mac地址
 * @property int $day 免费体验天数
 * @property int $expire_time 过期时间
 */
class OttProbation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_probation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['day', 'expire_time'], 'integer'],
            [['mac'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mac' => 'mac地址',
            'day' => '免费体验天数',
            'expire_time' => '过期时间',
        ];
    }
}
