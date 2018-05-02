<?php
namespace api\models;

use api\components\Formatter;
use backend\models\Mac;
use yii\base\Model;

class MacLoginForm extends Model
{

    public $mac;
    private $_user;

    public function attributeLabels()
    {
        return [
            'mac' => '机器码',
        ];
    }


    public function rules()
    {
        return [
            // username and password are both required
            [['mac'], 'required','message' => Formatter::EMPTY_LOGIN_ERROR],

        ];
    }

    /**
     * 使用用户名和密码登录
     */
    public function login()
    {
        if ($this->validate())
        {
            $this->_user = $this->getUser();
            if (is_null($this->_user)) {
                $this->addError('mac',Formatter::MAC_NOT_EXIST);
                return false;
            }

            if ($this->_user->use_flag == Mac::EXPIRED) {
                $this->addError('mac',Formatter::MAC_EXPIRE);
                return false;
            }

            if ($this->_user->use_flag == Mac::FORBIDDEN) {
                $this->addError('mac',Formatter::MAC_FORBIDDEN);
                return false;
            }

            $this->_user->logintime = date('Y-m-d H:i:s');
            $this->_user->access_token = $this->_user->generateAccessToken();
            $this->_user->access_token_expire = time() + 86400;

            if ($this->_user->save(false)) {
                return $this->_user;
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
            $this->_user = Mac::findIdentity($this->mac);
        }
        return $this->_user;
    }

}