<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/16
 * Time: 11:40
 */

namespace console\queues;

use yii\base\BaseObject;
use console\script\AnnaIptv;
use console\script\AnnaOtt;
use console\script\AnnaParade;

class ClientSyncJob extends BaseObject implements \yii\queue\JobInterface
{
    public $type;
    public $client;

    public function execute($queue)
    {
        if ($this->type == 'parade') {
            $this->annaParade();
        } else if ($this->type == 'ott') {
            $this->annaOtt();
        } else {
            $this->annaIptv();
        }
    }

    /**
     * 安娜OTT 预告入口
     */
    private function annaParade()
    {
        $annaParade = new AnnaParade($this);
        $annaParade->dealParade();
    }

    /**
     * 安娜OTT列表入口
     */
    private function annaOtt()
    {
        $annaOtt = new AnnaOtt($this);
        $annaOtt->dealOTT();
    }

    /**
     * 安娜IPTV脚本入口
     */
    private function annaIptv()
    {
        $annaIptv = new AnnaIptv($this);
        $annaIptv->dealIPTV();
    }

}