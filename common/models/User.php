<?php
namespace common\models;

use backend\models\OttOrder;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\filters\RateLimitInterface;
use yii\web\IdentityInterface;
use yii\web\Linkable;

/**
 * This is the model class for table "yii2_user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $access_token
 * @property int $access_token_expire API access_token 过期时间
 * @property int $allowance 剩余请求次数
 * @property int $allowance_updated_at 最后一次请求更新时间
 * @property string $is_vip
 * @property int $vip_expire_time
 * @property int $identity_type 会员类型
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static $vipType = [
        'Trial member',
        'Paid member'
    ];

    public static function getVipType()
    {
        $vipTypes = self::$vipType;
        array_walk($vipTypes, function(&$v, $k) {
            $v = Yii::t('backend', $v);
        });

        return $vipTypes;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['email', 'default', 'value' => ''],
            ['identity_type', 'default', 'value' => '0']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
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
     * API access_token 与IP绑定在一起
     * @return string
     */
    public function generateAccessToken()
    {
        $this->access_token = md5(Yii::$app->request->userIP) . "-" . Yii::$app->security->generateRandomString() ;
        return $this->access_token;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
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
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }



    public function fields()
    {
        return [
            'id',
            'username',
            'email',
            'status',
            'created_at',
            'updated_at',
            'is_vip',
            'vip_expire_time'
        ];
    }

    //RateLimitInterface 接口限制 出问题再做限制

    // Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
    /*public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save(false);
    }

    public function getRateLimit($request, $action)
    {
        //每天只运行请求5000次
        return [
            1, 10
        ];
    }*/

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'is_vip' => '会员',
            'vip_expire_time' => '过期时间',
            'identity_type' => '会员类型',
            'access_token_expire' => 'Token 过期时间'
            ];
    }

    public function beforeDelete()
    {
        // 删除用户相关的数据
        Order::deleteAll(['order_uid' => $this->username]);
        OttOrder::deleteAll(['uid' => $this->username]);

        return true;
    }

    public function getOttOrder()
    {
        return $this->hasMany(OttOrder::className(), ['uid' => 'username']);
    }

}
