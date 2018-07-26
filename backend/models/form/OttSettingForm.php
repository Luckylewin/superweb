<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/26
 * Time: 15:16
 */

namespace backend\models\form;


use backend\models\Config;
use yii\base\Model;
use yii\helpers\Json;

class OttSettingForm extends Model
{
    public $mode;
    public $free_day;

    public $mode_select =   [
        '0' => '模式一 : 免费',
        '1' => '模式二 : 会员收费',
        '2' => '模式三 : 按分类收费'
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode', 'free_day'], 'required'],
            ['mode', 'string'],
            ['free_day', 'integer', 'min' => 1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mode' => '收费模式',
            'free_day' => '免费使用(天)'
        ];
    }

    public function init()
    {
        $config = Config::findOne(['keyid' => 'ottcharge']);
        if (is_null($config)) {
            $config = new Config();
            $config->keyid = 'ottcharge';
            $config->save(false);
        }
        $data = json_decode($config->data, true);
        $this->mode = isset($data['mode']) ? $data['mode'] : 1;
        $this->free_day = isset($data['free_day']) ? $data['free_day'] : 0;


        return parent::init();
    }

    public function save()
    {
        $this->validate();
        $config = Config::findOne(['keyid' => 'ottcharge']);
        if ($config) {
            $data = json_decode($config->data, true);
            $data['mode'] = $this->mode;
            $data['free_day'] = $this->free_day;

            $config->data = Json::encode($data);
            $config->save(false);

            return true;
        }

        return false;
    }

}