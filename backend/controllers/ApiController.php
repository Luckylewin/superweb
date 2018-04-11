<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;

class ApiController extends BaseController
{
    public function actionIndex()
    {

    }

    /**
     * 返回阿里云OSS签名
     * @return mixed
     */
    public function actionOssUpload($dir)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dir = Yii::$app->request->get('dir', 'user-dir/');
        $oss = Yii::$app->Aliyunoss;

        return $oss->getSignature($dir);
    }
}
