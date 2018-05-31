<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:01
 */

namespace console\controllers;


use console\models\parade\beginsport;
use console\models\parade\cctv5;
use console\models\parade\espn;
use console\models\parade\eurosport;
use console\models\parade\sfrsport;
use console\models\parade\skysport;
use console\models\parade\sportnet;
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

    /**
     *
     */
    public function actionEspn()
    {
        $espn = new espn();
        $espn->start();
    }

    /**
     *
     */
    public function actionSport()
    {
        $sport = new sportnet();
        $sport->start();
    }

    public function actionSfr()
    {
        $sfr = new sfrsport();
        $sfr->start();
    }

    public function actionEuro()
    {
        $euro = new eurosport();
        $euro->start();
    }

    public function actionBegin()
    {
        $begin = new beginsport();
        $begin->start();
    }

    public function actionCctv5()
    {
        $cctv5 = new cctv5();
        $cctv5->start();
    }

}