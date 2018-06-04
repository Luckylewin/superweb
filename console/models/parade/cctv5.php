<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/31
 * Time: 17:14
 */

namespace console\models\parade;


use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class cctv5 extends CommonParade implements collector
{
    public $url = 'http://sports.cctv.com/nba/show/data/EPG/{DATE}.html?89055392';//20180601
    public $url2 = 'http://sports.cctv.com/nba/show/data/EPG/{DATE}.html?6039251';

    public function start()
    {
        $tasks = $this->getUrlGroup();
        foreach ($tasks as $task) {
            $this->getOneDay($task['url'], $task['date'], $task['name']);
            $this->_sleep(2,4);
        }

    }

    public function getOneDay($url, $date, $name)
    {
        $snnopy = MySnnopy::init();
        $snnopy->fetch($url);
        $data = $snnopy->results;

        $data = json_decode($data, true);
        $paradeData = [];
        foreach ($data['sportsEPG'] as $value) {

            $paradeData[] = [
                'parade_name' => $value['programTitle'],
                'parade_time' => $value['programTime']
            ];
        }

        $this->createParade($name, $date, $paradeData, __CLASS__, $url);
    }

    public function getUrlGroup()
    {
        $tasks = $this->getFutureTime(6, 'Ymd');
        array_walk($tasks, function(&$v) {
            $v['url'] = str_replace('{DATE}', $v['param'], $this->url);
            $v['name'] = 'CCTV5';
        });

        $tasks2 = $this->getFutureTime(6, 'Ymd');
        array_walk($tasks2, function(&$v) {
            $v['url'] = str_replace('{DATE}', $v['param'], $this->url2);
            $v['name'] = 'CCTV5+';
        });

        return ArrayHelper::merge($tasks, $tasks2);
    }
}