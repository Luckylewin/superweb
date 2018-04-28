<?php
namespace api\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class ApiSignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '此用户名已经被占用.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

           /* ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '此邮箱已被占用.'],*/

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'email' => '邮箱'
        ];
    }

    /**
     *  Signs user up.
     * @return array|User|null
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateAccessToken();
        $user->access_token_expire = time() + 86400 * 30;

        $user->save();

        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'access_token' => $user->access_token,
            'access_token_expire' => $user->access_token_expire,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'is_vip' => 0,
            'vip_expire_time' => 0,
        ];

    }
}
