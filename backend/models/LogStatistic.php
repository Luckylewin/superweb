<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "log_statistic".
 *
 * @property int $id
 * @property string $year 年份
 * @property string $date 日期
 * @property string $total 请求总数
 * @property string $watch 节目播放请求
 * @property string $token token请求
 * @property string $ott_list ott列表下载
 * @property string $iptv_list 点播列表下载
 */
class LogStatistic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_statistic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'total', 'token', 'ott_list', 'iptv_list'], 'required'],
            [['date'], 'safe'],
            [['year'], 'string', 'max' => 255],
            [['total', 'watch', 'token', 'ott_list', 'iptv_list'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => '年份',
            'date' => '日期',
            'total' => '请求总数',
            'watch' => '节目播放请求',
            'token' => 'token请求',
            'ott_list' => 'ott列表下载',
            'iptv_list' => '点播列表下载',
        ];
    }
}
