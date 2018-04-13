<?php

namespace backend\models;

use Yii;

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
 */
class Karaoke extends \yii\db\ActiveRecord
{
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
            [['updatetime', 'utime', 'is_del'], 'safe'],
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
            'albumName' => '标题',
            'albumImage' => '封面',
            'tid' => 'Tid',
            'mainActor' => '演员/歌手',
            'directors' => '导演',
            'tags' => '标签',
            'info' => '描述信息',
            'area' => '地区/语言',
            'keywords' => '关键字',
            'wflag' => 'Wflag',
            'year' => '年份',
            'mod_version' => 'Mod Version',
            'updatetime' => '更新时间',
            'totalDuration' => '集数',
            'flag' => 'Flag',
            'hit_count' => '点击数',
            'voole_id' => 'Voole ID',
            'price' => '价格',
            'is_finish' => '是否完成',
            'yesterday_viewed' => '昨日收看',
            'utime' => 'Utime',
            'url' => 'Url',
            'act_img' => '真实图片地址',
            'download_flag' => '是否下载',
            'is_del' => '是否软删除',
            'sort' => '排序',
        ];
    }

    public static function getLang()
    {

        return [
            'Vietnamese' => '越南语',
            'Chinese' => '中文',
            'English' => '英语',
            'Korean' => '韩语',
            'French' => '法语',
            'Other' => '其它',
        ];
    }

    public function beforeSave($insert)
    {
       if (parent::beforeSave($insert)) {
            $this->updatetime = date('Y-m-d H:i:s');
       }
       return true;
    }

}
