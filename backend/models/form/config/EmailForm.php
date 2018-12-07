<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 11:16
 */

namespace backend\models\form\config;

use Yii;

class EmailForm extends ConfigForm
{
    use LoadData;

    public $SMTP_HOST;
    public $SMTP_PORT;
    public $SMTP_AUTHENTICATE;
    public $ENCRYPTION;
    public $EMAIL_USERNAME;
    public $EMAIL_PASSWORD;
    public $EMAIL_NICKNAME;

    public function rules()
    {
       return [
           [['SMTP_HOST','SMTP_PORT', 'SMTP_AUTHENTICATE', 'ENCRYPTION', 'EMAIL_USERNAME', 'EMAIL_PASSWORD','EMAIL_NICKNAME'], 'required'],
           ['EMAIL_USERNAME', 'email']
        ];
    }

    public function attributeLabels()
    {
       return [
           'SMTP_HOST' => Yii::t('backend', 'SMTP HOST'),
           'SMTP_PORT' => Yii::t('backend', 'SMTP PORT'),
           'SMTP_AUTHENTICATE' => Yii::t('backend', 'SMTP AUTHENTICATE'),
           'ENCRYPTION' => Yii::t('backend', 'ENCRYPTION'),
           'EMAIL_USERNAME' => Yii::t('backend', 'EMAIL USERNAME'),
           'EMAIL_PASSWORD' => Yii::t('backend', 'EMAIL PASSWORD'),
           'EMAIL_NICKNAME' => Yii::t('backend', 'EMAIL NICKNAME'),
       ];
    }

    public function setData()
    {
        $this->iniData = [
            'EMAIL' => [
                'HOST' => $this->SMTP_HOST,
                'PORT' => $this->SMTP_PORT,
                'AUTHENTICATE' => $this->SMTP_AUTHENTICATE,
                'ENCRYPTION' => $this->ENCRYPTION,
                'USERNAME' => $this->EMAIL_USERNAME,
                'PASSWORD' => $this->EMAIL_PASSWORD,
                'NICKNAME' => $this->EMAIL_NICKNAME
            ]
        ];

        return $this;
    }

}