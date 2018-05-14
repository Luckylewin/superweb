<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

/**
 * This is the model class for table "iptv_vodlink".
 *
 * @property int $id
 * @property int $video_id 关联剧集
 * @property string $url
 * @property string $hd_url 高清
 * @property int $episode 剧集
 * @property string $plot 剧情
 */
class Vodlink extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_vodlink';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_id', 'episode'], 'integer'],
            [['url'], 'required'],
            [['url', 'hd_url'], 'string', 'max' => 255],
            [['plot'], 'string', 'max' => 1000],
            ['episode', 'filter', 'filter' => 'intval'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'video_id' => '关联剧集',
            'url' => 'URL',
            'hd_url' => '高清URl',
            'episode' => '剧集',
            'plot' => '剧情',
        ];
    }

    public function fields()
    {
        $parentField = parent::fields();
        unset($parentField['video_id']);
        return $parentField;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($insert) {
            $count = self::find()->where(['video_id' => $this->video_id])->count('id');
            if ($count >= 1) {
                $vodInfo = $this->vodInfo;
                $vodInfo->vod_multiple = 1;
                $vodInfo->save(false);
            }

        }
        return true;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $count = self::find()->where(['video_id' => $this->video_id])->count('id');
        if ($count <= 1) {
            $vodInfo = $this->vodInfo;
            $vodInfo->vod_multiple = 0;
            $vodInfo->save(false);
        }

        return true;
    }

    public function getVodInfo()
    {
        return $this->hasOne(Vod::className(), ['vod_id' => 'video_id']);
    }

    /**
     * 获取最大集数
     * @param $vod_id
     * @return mixed
     */
    public function getMaxEpisode($vod_id)
    {
        return self::find()->where(['video_id' => $vod_id])->max('episode');
    }

    /**
     * 获取下一集数
     * @param $vod_id
     * @return int|mixed
     */
    public function getNextEpisode($vod_id)
    {
        $maxEpisode = $this->getMaxEpisode($vod_id);
        if (is_null($maxEpisode)) {
            return 1;
        }
        return $maxEpisode + 1;
    }

}
