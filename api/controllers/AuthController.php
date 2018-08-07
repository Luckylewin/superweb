<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/22
 * Time: 14:31
 */

namespace api\controllers;

use Yii;
use api\models\MacLoginForm;
use api\components\Formatter;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class AuthController extends ActiveController
{
    public $modelClass = 'common\models\Mac';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except' => ['token']
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
        if ($action->id == 'token') {
            $request = Yii::$app->request;
            $sign = $request->post('signature');
            $username = $request->post('mac');
            $timestamp = $request->post('timestamp');

            if ( $sign != md5(md5($username . $timestamp) . md5('topthinker' . $timestamp)) ) {
                throw new ForbiddenHttpException('invalid request');
            }
        }

        return true;
    }

    public function actionToken()
    {
        $request = Yii::$app->request;
        $loginModel = new MacLoginForm();
        $loginModel->mac = $request->post('mac');

        if ( $user = $loginModel->login() ) {
            return Formatter::format($user);
        }

        $errorCode = current($loginModel->getErrorSummary(false));
        $errorMessage = Formatter::getMessage($errorCode);
        return Formatter::format(null,$errorCode, $errorMessage);
    }


}