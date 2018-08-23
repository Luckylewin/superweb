<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "iptv_middle_parade".
 *
 * @property string $genre 分类
 * @property string $channel 频道
 * @property string $parade_content 预告内容
 */
class MiddleParade extends \yii\db\ActiveRecord
{

    static public function primaryKey()
    {
        return ['channel', 'genre'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_middle_parade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parade_content'], 'string'],
            [['genre'], 'string', 'max' => 30],
            [['channel'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'genre' => '分类',
            'channel' => '频道',
            'parade_content' => '预告内容',
        ];
    }
}
