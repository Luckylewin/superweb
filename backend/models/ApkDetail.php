<?php

namespace backend\models;

use common\components\Func;
use Yii;
use common\oss\Aliyunoss;

/**
 * This is the model class for table "apk_detail".
 *
 * @property int $ID
 * @property int $apk_ID
 * @property string $type
 * @property string $ver
 * @property string $md5
 * @property string $url
 * @property string $content
 * @property int $sort
 * @property string $force_update
 */
class ApkDetail extends \yii\db\ActiveRecord
{

    public $dir = 'Android/apk/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apk_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apk_ID', 'ver', 'md5', 'url', 'content'], 'required'],
            [['apk_ID', 'sort'], 'integer'],
            [['url', 'content'], 'string'],
            [['type', 'ver', 'md5'], 'string', 'max' => 255],
            [['force_update'], 'string', 'max' => 1],
            ['type','default','value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'apk_ID' => Yii::t('backend', 'APK Type'),
            'type' => Yii::t('backend', 'Type'),
            'ver' => Yii::t('backend', 'Version number'),
            'md5' => 'md5',
            'url' => Yii::t('backend', 'Download link'),
            'content' => Yii::t('backend', 'Version update content'),
            'sort' => Yii::t('backend', 'Sort'),
            'force_update' => Yii::t('backend', 'Whether to force an update'),
        ];
    }

    public function getApkName()
    {
        return $this->hasOne(ApkList::className(), ['ID' => 'apk_ID']);
    }

    public function getTrueUrl()
    {
        return Func::getAccessUrl($this->url, 3600);
    }

}
