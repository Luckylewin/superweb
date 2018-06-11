<?php

namespace backend\controllers;

use backend\models\Scheme;
use Yii;
use yii\web\Response;

class ApiController extends BaseController
{
    public function actionScheme()
    {
        $scheme = Scheme::find()->select('id,schemeName')->asArray()->all();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $scheme;
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
