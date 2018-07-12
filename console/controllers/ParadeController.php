<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:01
 */

namespace console\controllers;

use backend\models\Parade;
use console\models\event\liveso;
use console\models\event\sportsmediawatch;
use console\models\event\zhiboba;
use console\models\parade\beginsport;
use console\models\parade\espn;
use console\models\parade\eurosport;
use console\models\parade\manmankan;
use console\models\parade\sfrsport;
use console\models\parade\skysport;
use console\models\parade\sportnet;
use console\models\parade\tsn;
use console\models\parade\vn;
use yii\console\Controller;

class ParadeController extends Controller
{

    public function actionHelp()
    {
        $list = [
            'sky','tsn','espn','sport','sfr','euro', 'begin', 'cctv5'
        ];
        foreach ($list as $action) {
            $this->stdout($action . PHP_EOL);
        }
    }

    public function clearExpire()
    {
        $date = date('Y-m-d' ,strtotime('-3 day'));
        Parade::deleteAll(['<=', 'parade_date', $date]);
    }

    public function actionNba()
    {
        try {

            $live = new sportsmediawatch();
            $live->start();

        } catch (\Exception $e) {}
    }

    public function actionZhiboba()
    {
        try {
            $live = new zhiboba();
            $live->start();
        } catch (\Exception $e) {}
    }

    public function actionCollect()
    {
        $this->clearExpire();
        //$this->actionVn();
        $this->actionSky();
        $this->actionTsn();
        $this->actionEspn();
        $this->actionSport();
        $this->actionSfr();
        $this->actionEuro();
        $this->actionBegin();
        $this->actionCctv5();
    }

    public function actionVn()
    {
       try {
           $vn = new vn();
           $vn->start();
       } catch (\Exception $e) {}
    }

    public function actionSky()
    {
        try {
            $sky = new skysport();
            $sky->start();
        } catch (\Exception $e) {}
    }

    public function actionTsn()
    {
       try {
           $tsn = new tsn();
           $tsn->start();
       } catch (\Exception $e) {}
    }

    /**
     *
     */
    public function actionEspn()
    {
        try {
            $espn = new espn();
            $espn->start();
        } catch (\Exception $e) {}
    }

    /**
     *
     */
    public function actionSport()
    {
       try {
           $sport = new sportnet();
           $sport->start();
       }catch (\Exception $e) {}
    }

    public function actionSfr()
    {
       try {
           $sfr = new sfrsport();
           $sfr->start();
       } catch (\Exception $e) {}
    }

    public function actionEuro()
    {
       try {
           $euro = new eurosport();
           $euro->start();
       } catch (\Exception $e) {}
    }

    public function actionBegin()
    {
        try {
            $begin = new beginsport();
            $begin->start();
        } catch (\Exception $e) {}
    }
    
    public function actionCctv5()
    {
        try {
            $kan = new manmankan();
            $kan->start();
        } catch (\Exception $e) {}
    }

    public function actionTest()
    {
        $kan = new manmankan();
        echo $kan->convertTimeZone('2018-06-15 10:38', 'timestamp', 9, 8);
    }

}