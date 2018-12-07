<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 9:26
 */

namespace backend\models\form\config;

use Yii;

class OtherForm extends ConfigForm
{
    use LoadData;

    public $nginx_port;
    public $nginx_dir;
    public $nginx_secret;

    public $oss_access_id;
    public $oss_access_key;
    public $oss_endpoint;
    public $oss_bucket;

    public $baidu_translate_id;
    public $baidu_translate_key;

    public function rules()
    {
        return [
            ['nginx_port', 'integer'],
            [['nginx_port','nginx_secret', 'nginx_dir'], 'required']
        ];
    }

    public function attributeLabels()
    {
       return [
            'nginx_port' => Yii::t('backend', 'nginx port'),
            'nginx_secret' => Yii::t('backend', 'nginx secret'),
            'nginx_dir' => Yii::t('backend', 'nginx dir'),
            'oss_access_id' => Yii::t('backend', 'Access_id'),
            'oss_access_key' => Yii::t('backend', 'Access_key'),
            'oss_endpoint' => Yii::t('backend', 'Endpoint'),
            'oss_bucket' => Yii::t('backend', 'Bucket'),
            'baidu_translate_id' => Yii::t('backend', 'translate_id'),
            'baidu_translate_key' => Yii::t('backend', 'translate_key'),
       ];
    }

    public function setData()
    {
        $this->iniData = [
            'NGINX' => [
                'UPLOAD_DIR' => $this->nginx_dir,
                'MEDIA_PORT' => $this->nginx_port,
                'SECRET' => $this->nginx_secret
            ],

            //阿里云OSS配置
            'OSS' =>[
                'ACCESS_ID' => $this->oss_access_id,    //ID
                'ACCESS_KEY' => $this->oss_access_key, // KEY
                'ENDPOINT' => $this->oss_endpoint, //指定区域
                'BUCKET' => $this->oss_bucket, //bucket
            ],

            // 百度翻译配置
            'BAIDU_TRANSLATE' => [
                'APP_ID' => $this->baidu_translate_id,//appID
                'SEC_KEY' => $this->baidu_translate_key //密钥
            ]
        ];

        return $this;
    }
}