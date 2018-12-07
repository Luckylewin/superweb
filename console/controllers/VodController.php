<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 13:22
 */

namespace console\controllers;


use console\collectors\local\VodCollector;
use console\models\Movie;
use yii\console\Controller;


class VodController extends Controller
{


    public function actionDisk()
    {
        $vodCollector = new VodCollector(new Movie());
        $vodCollector->doCollect();
    }


}