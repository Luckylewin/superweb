<?php
namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\Admin as User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;
    public $errorTime;
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'verifyCode'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['verifyCode','captcha', 'message' => '验证码错误', 'captchaAction'=>'site/captcha',]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'   => '用户名',
            'password'   => '密码',
            'verifyCode' => '验证码',
            'errorTime'  => '错误登录次数'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名密码不匹配');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        // 判断是否半小时之内连续错误登录10次
        $cache = Yii::$app->cache;
        $ip    = Yii::$app->request->getRemoteIP();

        if ($cache->get($ip) >= 30) {
            $this->addError('errorTime', '错误登录次数太多，请再半小时后再尝试');
            return false;
        }

        if ($this->validate()) {
            $user = $this->getUser();
            $user->last_login_time = time();
            $user->last_login_ip = ip2long(Yii::$app->request->getUserIP());
            $user->save(false);
            return Yii::$app->user->login($user, $this->rememberMe ? 86400 : 0);
        } else {
            if ($cache->exists($ip) == false) {
                $cache->set($ip, 1, 1800);
            } else {
                $cache->set($ip, $cache->get($ip) + 1, 1800);
            }
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
