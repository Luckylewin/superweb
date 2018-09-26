<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "firmware_detail".
 *
 * @property int $id
 * @property int $firmware_id 关联Id
 * @property string $ver 版本号
 * @property string $md5 文件md5
 * @property string $url 资源地址
 * @property string $content 更新内容
 * @property int $sort 排序
 * @property string $force_update 是否强制更新
 * @property string $is_use 是否使用
 */
class FirmwareDetail extends \yii\db\ActiveRecord
{
    public $dir = 'firmware/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'firmware_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firmware_id', 'ver', 'md5', 'url', 'content'], 'required'],
            [['firmware_id', 'sort'], 'integer'],
            [['url', 'content'], 'string'],
            [['ver', 'md5'], 'string', 'max' => 32],
            [['force_update', 'is_use'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firmware_id' => 'Firmware ID',
            'ver' => Yii::t('backend', 'Version'),
            'md5' => 'md5',
            'url' => Yii::t('backend', 'Resource'),
            'content' => Yii::t('backend', 'Post Content'),
            'sort' => Yii::t('backend', 'Sort'),
            'force_update' => Yii::t('backend', 'Whether to force an update'),
            'is_use' => Yii::t('backend', 'Is Available'),
        ];
    }

    public function getFirmware()
    {
        return $this->hasOne(FirmwareClass::className(), ['id' => 'firmware_id']);
    }
}
