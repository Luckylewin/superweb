<?php

namespace backend\models;

use Yii;
use common\models\OttChannel;
use yii\helpers\Json;

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
            [['match_data'], 'safe'],
            [['title', 'live_match', 'sort'], 'string', 'max' => 255],
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

    /**
     *
     */
    public function beforeValidate()
    {
       parent::beforeValidate();
       $this->time = strtotime($this->time);
       $this->base_time = strtotime($this->time);
       return true;
    }

    public function initData($post)
    {
        $match_data = [];
        foreach ($post['channel_id'] as $key => $channel_id) {
            $channelObject = OttChannel::findOne($channel_id);
            $match_data[] = [
                'channel_language' => $post['language'][$key],
                'main_class' => $post['main_class'][$key],
                'channel_name' => $channelObject->name,
                'channel_id' => $channelObject->channel_number,
                'channel_icon' => $channelObject->image
            ];
        }

        if (empty($match_data)) {
            return false;
        }

        $this->match_data = Json::encode($match_data);
        return true;
    }

}
