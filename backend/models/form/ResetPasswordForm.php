<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/20
 * Time: 10:55
 */

namespace backend\models\form;

use backend\models\Admin;
use Yii;
use yii\base\Model;

class ResetPasswordForm extends Model
{

    public $password;
    public $password_confirm;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_confirm','compare', 'compareAttribute' => 'password', 'message' => '两次密码输入不一致']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => '密码',
            'password_confirm' => '密码确认'
        ];
    }

    public function resetPassword()
    {
        if (!$this->validate()) {
            return null;
        }

        $admin = Admin::findOne(['id' => Yii::$app->user->id]);
        $admin->setPassword($this->password);

        return $admin->save(false);
    }
}