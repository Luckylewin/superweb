<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/22
 * Time: 14:31
 */

namespace api\controllers;

use Yii;
use api\models\ApiLoginForm;
use api\models\ApiSignupForm;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $loginModel = new ApiLoginForm();
        $loginModel->username = $request->post('username');
        $loginModel->password = $request->post('password');

        if ( $user = $loginModel->login() ) {
            return $user;
        }

        return $loginModel;
    }

    public function actionSignup()
    {
        $signupModel = new ApiSignupForm();

        $signupModel->load(Yii::$app->request->post(),'');

        if ($user = $signupModel->signup()) {
            return $user;
        }

        return $signupModel;
    }

}