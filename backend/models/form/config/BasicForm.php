<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/6
 * Time: 18:09
 */
namespace backend\models\form\config;

use Yii;

class BasicForm extends ConfigForm
{
    use LoadData;

    public $name;
    public $host;
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'host'], 'required'],
            [['name', 'host'], 'string'],
            ['email','email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('backend', 'System Name'),
            'host' => Yii::t('backend','System Hostname'),
            'email' => Yii::t('backend', 'Email')
        ];
    }

    public function setData()
    {
        $this->iniData = [
             'APP_NAME' => $this->name,
             'APP_HOST' => $this->host,
             'APP_EMAIL' => $this->email
        ];

        return $this;
    }

}