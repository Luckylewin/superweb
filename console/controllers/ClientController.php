<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/5
 * Time: 15:31
 */

namespace console\controllers;


use console\script\AnnaIptv;
use console\script\AnnaOtt;
use console\script\AnnaParade;
use yii\console\Controller;


class ClientController extends Controller
{
    /**
     * 安娜OTT 预告入口
     */
    public function actionAnnaParade()
    {
        $annaParade = new AnnaParade($this);
        $annaParade->dealParade();
    }

    /**
     * 安娜OTT列表入口
     */
    public function actionAnnaOtt()
    {
        $annaOtt = new AnnaOtt($this);
        $annaOtt->dealOTT();
    }

    /**
     * 安娜IPTV脚本入口
     */
    public function actionAnnaIptv()
    {
        $annaIptv = new AnnaIptv($this);
        $annaIptv->dealIPTV();
    }



}