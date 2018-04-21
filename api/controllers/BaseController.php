<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/21
 * Time: 17:42
 */

namespace api\controllers;

use Yii;
use yii\web\Response;


class BaseController extends \yii\web\Controller
{
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
           return $this->response($exception);
        }
    }

    public function response($data, $status= 'success')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $data;
        Yii::$app->response->send();
    }

}