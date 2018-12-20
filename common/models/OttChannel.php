<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\OttBanner;
use backend\models\OttRecommend;

/**
 * This is the model class for table "ott_channel".
 *
 * @property int $id
 * @property int $sub_class_id 关联id
 * @property string $name 名称
 * @property string $zh_name 中文名称
 * @property string $keywords 关键字
 * @property int $sort 排序
 * @property int $use_flag
 * @property int $channel_number 序列号
 * @property string $image 图标
 * @property string $alias_name 别名
 * @property string $is_recommend 是否被推荐
 * @property string $rebroadcast_use_flag 回播可用
 * @property string $rebroadcast_method 回播算法
 * @property string $shifting_use_flag 时移可用
 * @property string $shifting_method 时移算法
 */
class OttChannel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_channel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sub_class_id', 'name', 'zh_name', 'keywords'], 'required'],
            [['sub_class_id', 'sort', 'use_flag', 'channel_number'], 'integer'],
            [['name', 'zh_name', 'keywords'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 255],
            [['alias_name'], 'string', 'max' => 100],
            [['sort','is_recommend'], 'default', 'value' => '0'],
            ['use_flag', 'default', 'value' => '1'],
            [['shifting_use_flag', 'rebroadcast_use_flag'], 'integer'],
            [['shifting_method','rebroadcast_method'], 'string'],
            ['channel_number','default','value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sub_class_id' => Yii::t('backend', 'Associated CLASS ID'),
            'name' => Yii::t('backend', 'Name'),
            'zh_name' => Yii::t('backend', 'Chinese Name'),
            'keywords' => Yii::t('backend', 'keyword'),
            'sort' => Yii::t('backend', 'Sort'),
            'use_flag' => Yii::t('backend', 'Is Available'),
            'channel_number' => Yii::t('backend', 'channel number'),
            'image' => Yii::t('backend', 'Icon'),
            'alias_name' => Yii::t('backend', 'Alias Name'),
            'is_recommend' => Yii::t('backend', 'Recommend'),
            'shifting_method' => Yii::t('backend', 'time shifting method'),
            'rebroadcast_method' => Yii::t('backend', 'rebroadcast method'),
            'rebroadcast_use_flag' => Yii::t('backend', 'rebroadcast use flag'),
            'shifting_use_flag' => Yii::t('backend', 'time shifting use flag')
        ];
    }

    /**
     * 获取上级分类
     * @return \yii\db\ActiveQuery
     */
    public function getSubClass()
    {
        return $this->hasOne(SubClass::className(), ['id' => 'sub_class_id']);
    }

    public function getMainClass()
    {
        return $this->hasOne(MainClass::className(), ['id' => 'main_class_id'])
                    ->via('subClass');
    }


    public function beforeDelete()
    {
        OttLink::deleteAll(['channel_id' => $this->id]);
        return true;
    }

    public function getOwnLink($where = null)
    {
        $query = $this->hasMany(OttLink::className(), ['channel_id' => 'id']);
        if ($where) {
            $query->where($where);
        }

        return $query;
    }

    public function getRecommend()
    {
        return $this->hasOne(OttRecommend::className(), [
            'channel_id' => 'id'
        ])->orderBy('ott_recommend.sort asc');
    }

    public function getBanner()
    {
        return $this->hasOne(OttBanner::className(), ['channel_id' => 'id']);
    }

    public static function getDropdownList($sub_class_id)
    {
        $channel = self::find()->where(['sub_class_id' => $sub_class_id])->asArray()->all();
        if (!empty($channel)) {

            return ArrayHelper::map($channel, 'id', 'name');
        }

        return [];
    }

    public static function globalSearch($value)
    {
        $channels = static::find()
                            ->alias('a')
                            ->joinWith('subClass')
                            ->where(['like', 'a.name', $value])
                            ->orWhere(['like', 'alias_name', $value])
                            ->select(['a.id','a.name','a.alias_name','a.sub_class_id'])
                            ->limit(10)
                            ->asArray()
                            ->all();

        $array = [];
        foreach ($channels as $channel) {
            $array[] = [$channel['subClass']['name'],$channel['id'], $channel['name'], $channel['alias_name']];
        }

        $result['result'] = $array;
        $channels = json_encode($result);
        $channels = Yii::$app->request->get('callback') . "({$channels})";
        $response = Yii::$app->response;
        Yii::$app->response->format = $response::FORMAT_HTML;

        return $channels;
    }

    public static function getChannelParadeInfo($mainClassName,$subClassName,$channelName,$lang)
    {
        $mainClass = MainClass::findOne(['name' => $mainClassName]);
        if ($mainClass) {
            $subClass = SubClass::findOne(['name' => $subClassName]);
            if ($subClass) {
                $channel = static::findOne(['name' => $channelName]);
                if ($channel) {
                    return  [
                        "channel_language" => $lang,
                        "main_class" =>  $mainClass->list_name,
                        "channel_name" =>  $channel->name,
                        "channel_id" => $channel->channel_number,
                        "channel_true_id" =>  $channel->id,
                        "channel_icon" =>  $channel->image,
                    ];
                }
            }
        }

        return false;
    }

}
