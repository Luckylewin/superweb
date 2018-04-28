<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sys_buy_record".
 *
 * @property int $id
 * @property int $vod_id
 * @property int $user_id
 */
class BuyRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_buy_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vod_id', 'user_id'], 'required'],
            [['vod_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vod_id' => 'Vod ID',
            'user_id' => 'User ID',
        ];
    }
}
