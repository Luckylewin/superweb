<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "albumName_karaoke".
 *
 * @property int $ID
 * @property string $albumName 标题
 * @property string $albumImage 封面
 * @property int $tid
 * @property string $mainActor 演员/歌手
 * @property string $directors 导演
 * @property string $tags 标签
 * @property string $info 描述信息
 * @property string $area 地区
 * @property string $keywords 关键字
 * @property string $wflag
 * @property int $year 年份
 * @property string $mod_version
 * @property string $updatetime 更新时间
 * @property string $totalDuration 集数
 * @property int $flag
 * @property int $hit_count 点击数
 * @property int $voole_id
 * @property int $price 价格
 * @property int $is_finish 是否完成
 * @property int $yesterday_viewed 昨日收看
 * @property string $utime
 * @property string $url
 * @property string $act_img 真实图片地址
 * @property string $is_del 是否软删除
 * @property string $sort 排序
 * @property int $download_flag 是否下载
 * @property int $source 来源
 */
class Karaoke extends \yii\db\ActiveRecord
{

    public static $delStatus = ['Valid', 'Invalid'];

    const LANG_VN = 'Vietnamese';
    const LANG_ZH = 'Chinese';
    const LANG_EN = 'English';
    const LANG_KR = 'Korean';
    const LANG_FR = 'French';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_karaoke';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['albumName', 'albumImage', 'info', 'year', 'url'], 'required'],
            [['albumName', 'albumImage', 'info'], 'string'],
            [['tid', 'year', 'flag', 'hit_count', 'voole_id', 'price', 'is_finish', 'yesterday_viewed', 'download_flag'], 'integer'],
            [['updatetime', 'utime', 'is_del', 'source'], 'safe'],
            [['mainActor', 'directors', 'tags', 'area', 'keywords', 'wflag', 'mod_version', 'totalDuration'], 'string', 'max' => 255],
            [['url', 'act_img'], 'string', 'max' => 200],
            [['wflag','tid','flag','tid','mod_version','totalDuration','flag','voole_id','price','is_finish','yesterday_viewed'], 'default' , 'value' => 1],
            [['utime','updatetime'],'default','value' => date('Y-m-d H:i:s')],
            [['sort','is_del'],'default', 'value' => '0']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'albumName' => Yii::t('backend', 'Title'),
            'albumImage' => Yii::t('backend', 'Cover'),
            'tid' => 'Tid',
            'mainActor' => Yii::t('backend', 'singer'),
            'directors' => Yii::t('backend', 'director'),
            'tags' => Yii::t('backend', 'TAG'),
            'info' => Yii::t('backend', 'Description'),
            'area' => Yii::t('backend', 'Area'),
            'keywords' => Yii::t('backend', 'Keyword'),
            'wflag' => 'Wflag',
            'year' => Yii::t('backend', 'year'),
            'mod_version' => 'Mod Version',
            'updatetime' => Yii::t('backend', 'Updated Time'),
            'totalDuration' => Yii::t('backend', 'Total Episodes'),
            'flag' => 'Flag',
            'hit_count' => Yii::t('backend', 'Click quantity'),
            'voole_id' => 'Voole ID',
            'price' => Yii::t('backend', 'Price'),
            'is_finish' => Yii::t('backend', 'Completion result'),
            'yesterday_viewed' => Yii::t('backend', 'Yesterday’s number'),
            'utime' => 'Utime',
            'url' => 'Url',
            'act_img' => Yii::t('backend', 'Real image address'),
            'download_flag' => Yii::t('backend', 'Download status'),
            'is_del' => Yii::t('backend', 'Valid Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'source' => Yii::t('backend', 'Source identifier'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => 'updatetime',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['utime', 'updatetime'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['utime']
                ],
                'value' => date('Y-m-d H:i:s')
            ]
        ];
    }

    public static function getLang()
    {

        return [
            'Vietnamese' => Yii::t('backend', 'Vietnamese'),
            'Chinese' => Yii::t('backend', 'Chinese'),
            'English' => Yii::t('backend', 'English'),
            'Korean' => Yii::t('backend', 'Korean'),
            'French' => Yii::t('backend', 'French'),
            'Other' => Yii::t('backend', 'Other'),
        ];
    }



    public function getStatus()
    {
        return Yii::t('backend', self::$delStatus[$this->is_del]);
    }

    public function beforeSave($insert)
    {
       if (parent::beforeSave($insert)) {
            $this->updatetime = date('Y-m-d H:i:s');
       }
       return true;
    }

}
