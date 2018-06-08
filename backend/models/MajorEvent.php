<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ott_major_event".
 *
 * @property int $id
 * @property int $time 世界时间
 * @property string $title 标题
 * @property string $live_match 对阵信息
 * @property int $base_time 比赛时间
 * @property string $match_data 匹配预告列表
 * @property string $sort 排序
 */
class MajorEvent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_major_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'title'], 'required'],
            [['time', 'base_time'], 'string'],
            [['title', 'live_match', 'match_data', 'sort'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => '日期',
            'title' => '赛事名称',
            'live_match' => '赛事信息',
            'base_time' => '比赛时间',
            'match_data' => '匹配预告列表',
            'sort' => '排序',
        ];
    }
}
