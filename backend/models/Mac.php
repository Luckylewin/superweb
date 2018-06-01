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
 */
class Mac extends \yii\db\ActiveRecord implements IdentityInterface
{
    const NOT_ACTIVE = 0;
    const NORMAL     = 1;
    const EXPIRED    = 2;
    const FORBIDDEN  = 3;

    public $client_name;
    public $unit;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mac';
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
            [['regtime', 'logintime', 'duetime', 'unit', 'client_name'], 'safe'],
            [['MAC', 'SN'], 'string', 'max' => 64],
            [['contract_time'], 'string', 'max' => 8],
            [['MAC', 'SN'], 'unique', 'targetAttribute' => ['MAC', 'SN']],
            [['MAC'], 'unique'],
            [['ver'], 'default', 'value'=>'0'],
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
            'MAC' => 'mac地址',
            'SN' => 'sn码',
            'use_flag' => '是否可用',
            'ver' => '版本',
            'regtime' => '注册时间',
            'logintime' => '登录时间',
            'type' => '类型',
            'duetime' => '过期时间',
            'contract_time' => '有效期',
            'client_name' => '所属客户'
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (MacDetail::find()->where(['MAC' => $this->MAC])->exists() == false) {
            $macDetail = new MacDetail();
            $macDetail->MAC = $this->MAC;
            $macDetail->client_id = !is_null($this->client_name) ? $this->client_name : '-1';

            $macDetail->save(false);
        };

        return true;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($this->isNewRecord == false) {
            $this->contract_time = str_replace(['year', 'month', 'day'], ['','',''], $this->contract_time);
        }
        $this->contract_time .=  (" " . $this->unit);
        unset($this->unit);

        return true;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $logs = $this->getRenewLog()->all();
            foreach ($logs as $log) {
                $log->delete();
            }
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
           self::NOT_ACTIVE => '未激活',
           self::NORMAL     => '可用',
           self::EXPIRED    => '过期',
           self::FORBIDDEN  => '禁用'
        ];
    }

    /**
     * 获取开通时长时间单位
     * @return array
     */
    public static function getContractList()
    {
        return [
            'year' => '年',
            'month' => '月',
            'day' => '天'
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
        $value = $this->getOnLine();
        $label = $value ? ['info', '在线'] : ['default', '离线'];

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

}
