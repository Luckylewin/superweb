<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "iptv_url_resolution".
 *
 * @property int $id
 * @property string $method 解析名称
 * @property string $c c语言
 * @property string $android 安卓
 * @property string $referer
 * @property int $expire_time 过期时间
 * @property string $url 目标url
 */
class IptvUrlResolution extends \yii\db\ActiveRecord
{
    public $c_i;
    public $c_ii;
    public $c_iii;

    public $android_i;
    public $android_ii;
    public $android_iii;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_url_resolution';
    }

    public function beforeValidate()
    {
       if (parent::beforeValidate()) {
           $c['i'] = array_filter(!empty($this->c_i)? $this->c_i : []);
           $c['ii'] = array_filter(!empty($this->c_ii)? $this->c_ii : []);
           $c['iii'] = array_filter(!empty($this->c_iii)? $this->c_iii : []);

           $android['i'] = array_filter(!empty($this->android_i) ? $this->android_i : []);
           $android['ii'] = array_filter(!empty($this->android_ii) ? $this->android_ii : []);
           $android['iii'] = array_filter(!empty($this->android_iii) ? $this->android_iii : []);

           $level = count($c['ii']) > 0 ? (count($c['iii']) > 0 ? 'iii' : 'ii') : 'i';

           $this->c =  json_encode(['regex_level'=> $level, 'regex'=>$c]);
           $this->android = json_encode(['preg_level'=>$level,'regex'=>$android]);

       }
        return true;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['method', 'c', 'android', 'referer', 'expire_time', 'url'], 'required'],
            [['expire_time'], 'integer'],
            [['method'], 'string', 'max' => 50],
            [['c', 'android'], 'string', 'max' => 600],
            [['referer', 'url'], 'string', 'max' => 100],
            [['c_i', 'c_ii', 'c_iii', 'android_i', 'android_ii','android_iii'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'method' => '解析名称',
            'c' => 'c语言',
            'android' => '安卓',
            'referer' => 'Referer',
            'expire_time' => '过期时间',
            'url' => '目标url',
        ];
    }
}
