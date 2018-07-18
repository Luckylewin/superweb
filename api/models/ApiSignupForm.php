<?php
namespace api\models;

use api\components\Formatter;
use backend\models\OttOrder;
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
            ['username', 'required', 'message' => Formatter::EMPTY_SIGNUP_ERROR],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Formatter::USERNAME_EXIST_ERROR],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email', 'skipOnEmpty' => true],
           /* ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '此邮箱已被占用.'],*/

            ['password', 'required', 'message' => Formatter::EMPTY_SIGNUP_ERROR],
            ['password', 'string', 'min' => 6, 'message' => Formatter::PASSWORD_TOO_SHORT_ERROR],
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

        $probation_expire = $this->_getProbationExpireTime();

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateAccessToken();
        $user->access_token_expire = time() + 86400 * 30;
        $user->is_vip = 1;
        $user->vip_expire_time = $probation_expire;

        $user->save();
        $probation_order = $this->addFreeUse($user, $probation_expire);

        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'access_token' => $user->access_token,
            'access_token_expire' => $user->access_token_expire,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'is_vip' => 1,
            'identity_type' => 0,
            'vip_expire_time' => $probation_order->expire_time
        ];
    }

    public function addFreeUse(User $user, $expire_time)
    {
        $order = new OttOrder();
        $order->uid = $user->username;
        $order->expire_time = $expire_time;
        $order->genre = 'probation';
        $order->is_valid = 1;
        $order->save(false);

        return $order;
    }

    private function _getProbationExpireTime()
    {
        return time() + 86400 * 7;
    }
}
