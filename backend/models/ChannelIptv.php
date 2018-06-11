<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:18
 */

namespace backend\models;


/**
 * This is the model class for table "channel_iptv".
 *
 * @property int $ID
 * @property int $ClassID
 * @property string $name
 * @property string $alias_name
 * @property string $keywords
 * @property int $sort
 * @property int $use_flag
 * @property int $serial_id
 * @property string $en_name
 * @property int $region_id
 * @property string $client
 * @property int $voole_id
 * @property string $eng_name
 * @property string $channelPic
 */
class ChannelIptv extends \yii\db\ActiveRecord
{

    public $dir = 'iptv/channel-icon/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_channel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ClassID', 'name',  'region_id'], 'required'],
            [['ClassID', 'sort', 'use_flag', 'serial_id', 'region_id', 'voole_id'], 'integer'],
            [['client'], 'string'],
            [['name', 'keywords', 'EPG', 'en_name'], 'string', 'max' => 255],
            [['eng_name', 'channelPic'], 'string', 'max' => 50],
            ['alias_name', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'ClassID' => '分类',
            'name' => '名称',
            'alias_name' => '别名',
            'keywords' => '关键字',
            'sort' => '排序',
            'use_flag' => '是否可用',
            'serial_id' => '频道号',
            'en_name' => '英文名称',
            'region_id' => '地区ID',
            'client' => '客户名称',
            'voole_id' => 'Voole ID',
            'eng_name' => '英文名称',
            'channelPic' => '频道图标',
        ];
    }

    public function getLink()
    {
        return $this->hasMany(Link::className(),['ChannelID' => 'ID']);
    }

    public function getClass()
    {
        return $this->hasOne(ClassIptv::className(), ['ID' => 'ClassID']);
    }
}
