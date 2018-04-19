<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:01
 */

namespace console\controllers;


use console\models\parade\vn;
use yii\console\Controller;

class ParadeController extends Controller
{
    public function actionVn()
    {
        $vn = new vn();
        $vn->start();
    }
}