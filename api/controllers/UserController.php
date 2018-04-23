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
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except' => ['login', 'signup']
        ];

        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['view', 'delete', 'update'])) {
            if ($model->id !== \Yii::$app->user->identity->getId())
                throw new ForbiddenHttpException('You can only visit Your resource.');
        }
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        if ($action->id == 'signup') {
            $request = Yii::$app->request;
            $sign = $request->post('signature');
            $username = $request->post('username');
            $password = $request->post('password');
            $timestamp = $request->post('timestamp');

            if ( $sign != md5($username . $password . $timestamp .  'supercinema') ) {
                throw new ForbiddenHttpException('invalid request');
            }
        }

        return true;
    }

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