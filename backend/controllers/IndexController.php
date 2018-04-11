<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/10
 * Time: 14:34
 */

namespace backend\controllers;


use yii\base\Controller;

class IndexController extends Controller
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
}