<?php
namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "sys_client".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $last_login_time
 * @property integer $last_login_ip
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    const SUPER_ADMIN = 'admin';

    public $password;

    const STATUS_DELETE = 0;
    const STATUS_ACTIVE = 1;

    public static $statusTexts = [
        self::STATUS_DELETE => 'off',
        self::STATUS_ACTIVE => 'on',
    ];

    public static $statusStyles = [
        self::STATUS_DELETE => 'label-warning',
        self::STATUS_ACTIVE => 'label-info',
    ];

    public static function tableName() {
        return '{{%admin}}';
    }

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules() {
        return [
            [['username', 'email'], 'required'],
            [['username'], 'string', 'length' => [5, 15]],
            [['password'], 'required', 'on' => ['create']],
            [['reg_ip', 'last_login_time', 'last_login_ip'], 'integer'],
            [['auth_key'], 'string', 'length' => 32],
            [['username', 'email'], 'unique'],
            [['email'], 'email'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [
                self::STATUS_ACTIVE, self::STATUS_DELETE
            ]],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['username', 'email', 'password', 'status'];
        $scenarios['update'] = ['username', 'email', 'password', 'status'];
        return $scenarios;
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => Yii::t('backend', 'username'),
            'auth_key' => 'Auth Key',
            'password' => Yii::t('backend', 'password'),
            'password_hash' => 'Password Hash',
            'email' => Yii::t('backend', 'mail'),
            'reg_ip' => Yii::t('backend', 'register ip'),
            'last_login_time' => Yii::t('backend', 'Last Logging Time'),
            'last_login_ip' => Yii::t('backend', 'Last Logging IP'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created Time'),
            'updated_at' => Yii::t('backend', 'Updated Time'),
            'user_role' => Yii::t('backend', 'Role'),
            'role' => Yii::t('backend', 'Group')
        ];
    }

    /**
     * 获取账号信息
     * @param  [int] $id [后台用户id]
     */
    public static function findIdentity($id)
    {
        return Yii::$app->cache->getOrSet('sys-admin', function() use ($id) {
            return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        }, 3600);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * 获取账号信息
     * @param  [string] $username [用户名]
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 获取账号主键值(id)
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * 获取账号auth_key值
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * 验证auth_key
     * @param  [string] $authKey [auth key]
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 验证用户密码
     * @param  [string] $password [用户密码]
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * 生成加密后的密码
     * @param [string] $password [用户密码]
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * 生成auth_key
     * @return [type] [description]
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 获取账号状态
     */
    public function getStatus() {
        return self::$statusTexts[$this->status];
    }

    /**
     * 获取账号状态样式
     */
    public function getStatusStyle() {
        return self::$statusStyles[$this->status];
    }

    /**
     * 获取状态
     */
    public function getStatusTexts() {
        return self::$statusTexts;
    }

    //获取用户所在的用户组
    public function getGroup() {
        return implode(',', array_keys(Yii::$app->authManager->getRolesByUser($this->id)));
    }

    public function getRole()
    {
        $role = Yii::$app->authManager->getRolesByUser($this->id);
        if ($role) {
            $role = current($role)->name;
        } else {
            $role = "未指定角色";
        }

        return $role;
    }

    public function beforeSave($insert) {
        if($this->isNewRecord) {
            $this->generateAuthKey();
            $this->reg_ip = ip2long(Yii::$app->getRequest()->getUserIP());
            $this->last_login_time = 0;
            $this->last_login_ip = 0;
        }
        if(!empty($this->password)) $this->setPassword($this->password);
        return parent::beforeSave($insert);
    }

    public function getScheme($field = null)
    {
        return $this->hasMany(Scheme::className(), ['id' => 'scheme_id'])
                    ->via('schemeItems')
                    ->asArray()
                    ->select($field == null ? '*' : $field)
                    ->all();
    }

    public function getSchemeItems()
    {
        return $this->hasMany(AdminToScheme::className(), [
           'admin_id' => 'id'
        ]);
    }

    public static function getCurrentUser()
    {
        return self::findOne(Yii::$app->user->getId());
    }

    public static function isSuperAdmin()
    {
        return self::SUPER_ADMIN == Yii::$app->user->identity->username;
    }

}
