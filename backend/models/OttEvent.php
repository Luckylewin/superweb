<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ott_event".
 *
 * @property int $id
 * @property string $event_name 名称
 * @property string $event_name_zh 中文名称
 * @property string $event_introduce 介绍
 * @property string $event_icon 图标
 * @property string $event_icon_big 大图标
 * @property int $sort 排序
 */
class OttEvent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_name'], 'required'],
            [['sort'], 'integer'],
            [['event_name', 'event_name_zh'], 'string', 'max' => 30],
            [['event_introduce', 'event_icon', 'event_icon_big'], 'string', 'max' => 255],
            ['sort', 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_name' => '名称',
            'event_name_zh' => '中文名称',
            'event_introduce' => '介绍',
            'event_icon' => '图标',
            'event_icon_big' => '大图标',
            'sort' => '排序',
        ];
    }


    static public function getDropDownList()
    {
        $data = self::find()->all();
        if (!empty($data)) {
            return ArrayHelper::map($data, 'id', 'event_name_zh');
        }

        return [];
    }

}
