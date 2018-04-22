<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

/**
 * This is the model class for table "sys_banner".
 *
 * @property int $id
 * @property int $vod_id 影片id
 * @property int $sort 排序
 * @property string $title 标题
 * @property string $description 描述
 * @property string $pic
 * @property string $pic_bg
 */
class Banner extends \yii\db\ActiveRecord implements Linkable
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vod_id', 'title',  'pic'], 'required'],
            [['id', 'vod_id', 'sort'], 'integer'],
            [['description'], 'string'],
            [['title', 'pic', 'pic_bg'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
            'vod_id' => '影片id',
            'sort' => '排序',
            'title' => '标题',
            'description' => '描述',
            'pic' => 'Pic',
            'pic_bg' => 'Pic Bg',
        ];
    }

    public function getLinks()
    {
        // TODO: Implement getLinks() method.
        return [
          Link::REL_SELF => Url::to(['banner/view', 'id'=> $this->id], true),
          'vod' => Url::to(['vod/view', 'id' => $this->vod_id], true)
        ];
    }
}
