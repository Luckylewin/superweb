<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:01
 */

namespace console\controllers;


use console\models\parade\espn;
use console\models\parade\skysport;
use console\models\parade\tsn;
use console\models\parade\vn;
use yii\console\Controller;

class ParadeController extends Controller
{
    public function actionVn()
    {
        $vn = new vn();
        $vn->start();
    }

    public function actionSky()
    {
        $sky = new skysport();
        $sky->start();
    }

    public function actionTsn()
    {
        $tsn = new tsn();
        $tsn->start();
    }

    public function actionEspn()
    {
        $espn = new espn();
        $espn->start();
    }
}