<?php

namespace backend\models;

use common\models\OttChannel;
use Yii;

/**
 * This is the model class for table "ott_banner".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $desc 详情
 * @property string $image 小图
 * @property string $image_big 大图
 * @property string $sort 排序
 * @property int $channel_id 频道id
 * @property string $url 资源地址
 */
class OttBanner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'image'], 'required'],
            [['channel_id'], 'integer'],
            [['title', 'desc', 'image', 'image_big', 'sort', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('backend', 'Title'),
            'desc' => Yii::t('backend', 'Description'),
            'image' => Yii::t('backend', 'Thumbnail'),
            'image_big' => Yii::t('backend', 'Big icon'),
            'sort' => Yii::t('backend', 'Sort'),
            'channel_id' => Yii::t('backend', 'Channel ID'),
            'url' => Yii::t('backend', 'Resource address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(OttChannel::className(), ['id' => 'channel_id']);
    }

}
