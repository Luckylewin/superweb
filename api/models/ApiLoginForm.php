<?php
namespace api\models;

use api\components\Formatter;
use backend\models\OttOrder;
use common\models\User;
use yii\base\Model;

class ApiLoginForm extends Model
{

    public $username;
    public $password;

    private $_user;

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码'
        ];
    }


    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required','message' => Formatter::EMPTY_LOGIN_ERROR],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * 验证密码
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Formatter::LOGIN_PASSWORD_ERROR);
            }
        }
    }


    /**
     * 使用用户名和密码登录
     */
    public function login()
    {
        if ($this->validate())
        {
            $this->_user = $this->getUser();
            $this->_user->access_token = $this->_user->generateAccessToken();
            $this->_user->access_token_expire = time() + 86400 * 7;
            //判断是否过期
            if ($this->_user->is_vip && time() > $this->_user->vip_expire_time) {
                $this->_user->is_vip = 0;
            }

            if ($this->_user->save(false)) {
                return [
                    'id' => $this->_user->id,
                    'username' => $this->_user->username,
                    'access_token' => $this->_user->access_token,
                    'access_token_expire' => $this->_user->access_token_expire,
                    'created_at' => $this->_user->created_at,
                    'updated_at' => $this->_user->updated_at,
                    'is_vip' => $this->_user->is_vip,
                    'vip_expire_time' => $this->_user->vip_expire_time,
                    'identity_type' => $this->_user->identity_type
                ];
            }

        }
        return false;
    }


    /**
     * 通过username查找用户
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

}