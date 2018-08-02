<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/10
 * Time: 14:34
 */

namespace backend\controllers;

use Yii;

class IndexController extends BaseController
{
    public function actionFrame()
    {
        $this->layout = false;
        return $this->render('frame');
    }

    public function actionIndex()
    {

        return $this->render('index');
    }

    /**
     * 设定语言： 1) 设置cookie,2) 跳转回原来的页面
     * 访问网址 - http://.../site/language?locale=zh-CN
     */
    public function actionLanguage($lang)
    {
        $cookies = Yii::$app->response->cookies;

        $cookies->add(new \yii\web\Cookie([
            'name' => 'language',
            'value' => $lang,
            'expire' => time() + 24640000
        ]));

        $this->goBack(Yii::$app->request->headers['Referer']);
    }
}