<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:01
 */

namespace console\controllers;

use backend\models\Parade;
use console\jobs\ParadeJob;
use console\collectors\event\NBA;
use console\collectors\event\zhiboba;
use console\collectors\parade\beginsport;
use console\collectors\parade\espn;
use console\collectors\parade\eurosport;
use console\collectors\parade\manmankan;
use console\collectors\parade\sfrsport;
use console\collectors\parade\skysport;
use console\collectors\parade\sportnet;
use console\collectors\parade\tsn;
use console\collectors\parade\vn;
use yii\console\Controller;


/**
 * 预告控制器
 * Class ParadeController
 * @package console\controllers
 */
class ParadeController extends Controller
{

    public function actionHelp()
    {
        $list = [
            'sky','tsn','espn','sport','sfr','euro', 'begin', 'cctv5','create-cache'
        ];
        
        foreach ($list as $action) {
            $this->stdout($action . PHP_EOL);
        }
    }

    public function actionCreateCache()
    {
        ParadeJob::generateCache();
    }

    public function clearExpire()
    {
        $date = date('Y-m-d' ,strtotime('-9 day'));
        Parade::deleteAll(['<=', 'parade_date', $date]);
    }

    public function actionNba()
    {
        try {
            //$live = new sportsmediawatch();
            $live = new NBA();
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


}