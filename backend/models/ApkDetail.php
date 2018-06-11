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
            'apk_ID' => 'APK类别',
            'type' => '类型',
            'ver' => '版本号',
            'md5' => 'md5值',
            'url' => '下载地址',
            'content' => '版本更新内容',
            'sort' => '排序',
            'force_update' => '是否强制更新',
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
