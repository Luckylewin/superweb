<?php

namespace backend\models;

use Yii;
use backend\components\MyRedis;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "mac".
 *
 * @property int $id id
 * @property string $MAC mac地址
 * @property string $SN sn码
 * @property int $use_flag 是否可用
 * @property string $ver 版本
 * @property string $regtime 注册时间
 * @property string $logintime 登录时间
 * @property int $type 类型
 * @property string $duetime 过期时间
 * @property string $contract_time 有效期
 * @property string $access_token 有效期
 * @property string $access_token_expire 有效期
 * @property int $identity_type 会员类型
 * @property int $client_id 关联的客户id
 * @property string $is_online 是否在线
 * @property string is_hide 隐藏内容状态
 */
class Mac extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $model;
    const NOT_ACTIVE = 0;
    const NORMAL     = 1;
    const EXPIRED    = 2;
    const FORBIDDEN  = 3;

    const HIDE = 1;
    const SHOW = 0;

    public $client_name;
    public $unit;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mac';
    }


    public function attributes ()
    {
        $attributes = parent::attributes();
        $attributes[] = 'unit';
        return $attributes;
    }

    public function getModel()
    {
        if (!$this->model) {
            $this->model = new Mac();
        }

        return $this->model;
    }

    public function scenarios()
    {
        return [
            'update' => ['is_hide', 'client_name'],
            'create' => ['is_hide', 'client_name', 'MAC', 'SN', 'contract_time'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MAC','SN', 'contract_time'], 'required'],
            [['MAC', 'SN'], 'unique'],
            [['contract_time'],'integer','min' => 1],
            [['use_flag', 'type'], 'integer'],
            [['regtime', 'logintime', 'duetime', 'unit', 'client_name', 'is_hide'], 'safe'],
            [['MAC', 'SN'], 'string', 'max' => 64],
            [['contract_time'], 'string', 'max' => 8],

            [['ver', 'identity_type', 'client_id', 'is_online'], 'default', 'value' => 0],
            [['regtime'], 'default', 'value' => date('Y-m-d H:i:s', time())],
            [['logintime'], 'default', 'value' => '']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MAC' => Yii::t('backend', 'Mac Address'),
            'SN' => Yii::t('backend', 'Sn Number'),
            'use_flag' => Yii::t('backend', 'Is Available'),
            'ver' => Yii::t('backend', 'Version'),
            'regtime' => Yii::t('backend', 'Registration Time'),
            'logintime' => Yii::t('backend', 'Login Time'),
            'type' => Yii::t('backend', 'Type'),
            'duetime' => Yii::t('backend', 'Expire Time'),
            'contract_time' => Yii::t('backend', 'Validity Period'),
            'client_name' => Yii::t('backend', 'Associated Client'),
            'is_online' => Yii::t('backend', 'is online'),
            'is_hide' => Yii::t('backend', 'is hide')
        ];
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($this->isNewRecord == false) {
            $this->contract_time = str_replace(['year', 'month', 'day'], ['','',''], $this->contract_time);
        } else {
            $this->regtime = date('Y-m-d H:i:s');
        }
        $this->contract_time .=  (" " . $this->unit);
        unset($this->unit);

        return true;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // 删除续费记录
            $logs = $this->getRenewLog()->all();
            foreach ($logs as $log) {
                $log->delete();
            }
            // 删除redis缓存

            $redis = Yii::$app->redis;
            $redis->select(MyRedis::REDIS_DEVICE_STATUS);
            $redis->del($this->MAC);
        }
        return true;
    }

    /**
     * 获取状态
     * @return array
     */
    public static function getUseFlagList()
    {
        return [
           self::NOT_ACTIVE => Yii::t('backend', 'Inactivated'),
           self::NORMAL     => Yii::t('backend', 'Available'),
           self::EXPIRED    => Yii::t('backend', 'Expired'),
           self::FORBIDDEN  => Yii::t('backend', 'Disable')
        ];
    }

    /**
     * 获取状态
     * @return array
     */
    public static function getIsHiDEList()
    {
        return [
            self::SHOW => Yii::t('backend', 'Show'),
            self::HIDE => Yii::t('backend', 'Hide'),
        ];
    }

    /**
     * 获取开通时长时间单位
     * @return array
     */
    public static function getContractList()
    {
        return [
            'year' => Yii::t('backend', 'year'),
            'month' => Yii::t('backend', 'month'),
            'day' => Yii::t('backend', 'day')
        ];
    }

    public function getUseFlag($index)
    {
        $list = self::getUseFlagList();
        return $list[$index];
    }

    public function getUseFlagWithLabel($index)
    {
        $value = $this->getUseFlag($index);
        $labels = ['default', 'info', 'warning', 'danger'];
        $label = $labels[$index];
        return "<label class='label label-{$label}'>{$value}</label>";
    }

    public function getDetail()
    {
        return $this->hasOne(MacDetail::className(), ['MAC' => 'MAC']);
    }

    public function getOnLine()
    {
        $redis = MyRedis::init(MyRedis::REDIS_DEVICE_STATUS);

        return $redis->hget($this->MAC, 'token') ? true : false;
    }

    public function getOnlineWithLabel()
    {
        $label = $this->is_online ? ['info', Yii::t('backend', 'Online')] : ['default', Yii::t('backend', 'Offline')];
        return "<label class='label label-{$label[0]}'>{$label[1]}</label>";
    }

    public function getRenewLog()
    {
        return $this->hasOne(RenewLog::className(),['mac' => 'MAC']);
    }

    public static function exportCSV($data)
    {
        $str = "MAC,SN\n";
        $str = iconv('utf-8','gb2312',$str);

        foreach ($data as $v) {
            $MAC = iconv('utf-8','gb2312',$v->MAC); //中文转码
            $SN = iconv('utf-8','gb2312',$v->SN);
            $str .= "\t".$MAC.","."\t".$SN."\n";
        }

        $filename = date('Ymd').'.csv'; //设置文件名
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        return $str;
    }

    public static function findIdentity($mac)
    {
        return static::findOne(['MAC' => $mac]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $identity = static::find()
                          ->where(['access_token' => $token])
                          ->andWhere(['>', 'access_token_expire', time()])
                          ->one();

        if ($identity) {
            if (md5(Yii::$app->request->remoteIP) != strstr($identity->access_token, '-', true)) {
                return false;
            }
        }

        return $identity;
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return '';
    }


    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }


    /**
     * API access_token 与IP绑定在一起
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateAccessToken()
    {
        $this->access_token = md5(Yii::$app->request->userIP) . "-" . Yii::$app->security->generateRandomString() ;
        return $this->access_token;
    }

    public function fields()
    {

        return [
            'MAC',
            'use_flag' => function($model) {
                return self::getUseFlag($model->use_flag);
            },
            'ver',
            'regtime',
            'logintime',
            'duetime',
            'contract_time',
            'access_token',
            'access_token_expire'
        ];
    }

    public static function getOnlineNum($hour = 1)
    {
        $time = date('Y-m-d H:i:s', time() - $hour * 3600);
        return self::find()->where(['>=', 'logintime', $time])->count('*');
    }

}
