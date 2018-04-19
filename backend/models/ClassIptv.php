<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:19
 */

namespace backend\models;


/**
 * This is the model class for table "class_iptv".
 *
 * @property int $ID
 * @property string $name
 * @property int $sort
 * @property int $use_flag
 * @property string $en_name
 * @property string $client
 * @property int $country_id
 * @property string $keyword 导入识别关键字
 * @property string $type
 * @property string $eng_name
 */
class ClassIptv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_iptv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['sort', 'use_flag', 'country_id'], 'integer'],
            [['client'], 'string'],
            [['name', 'en_name', 'keyword'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20],
            [['eng_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'name' => '分类',
            'sort' => '排序',
            'use_flag' => '是否可用',
            'en_name' => 'En Name',
            'client' => '客户',
            'country_id' => '所属国家',
            'keyword' => '导入识别关键字',
            'type' => '分类',
            'eng_name' => '英文名称',
        ];
    }

    public function getChannels()
    {
        return $this->hasMany(ChannelIptv::className(), ['ClassID' => 'ID']);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }
}