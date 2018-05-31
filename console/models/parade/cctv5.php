<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/31
 * Time: 17:14
 */

namespace console\models\parade;


use Symfony\Component\DomCrawler\Crawler;
use yii\helpers\ArrayHelper;

class cctv5 extends CommonParade implements collector
{
    public $url = 'https://www.tvmao.com/program/CCTV-CCTV5-w{NUM}.html';
    public $url2 = 'https://www.tvmao.com/program/CCTV-CCTV5-PLUS-w{NUM}.html';
    public $debug = false;

    public function start()
    {
        $tasks = $this->getUrlGroup();
        foreach ($tasks as $task) {
            $this->getOneDay($task['url'], $task['date'], $task['name']);
            $this->_sleep(2, 5);
        }
    }

    public function getOneDay($url, $date, $name)
    {
        $dom = $this->getDom($url);
        $paradeData = $dom->filter('#pgrow')->filterXPath('//li[@class]')->each(function(Crawler $node) {
            return [
                'parade_name' => $node->filter('.p_show')->text(),
                'parade_time' => $node->filter('.am')->text()
            ];
        });

        $this->createParade($name, $date, $paradeData);
    }

    public function getUrlGroup()
    {
        $tasks = $this->getWeekTime();
        array_walk($tasks, function(&$v) {
            $v['url'] = str_replace('{NUM}', $v['week'], $this->url);
            $v['name'] = 'CCTV5';
        });

        $tasks2 = $this->getWeekTime();
        array_walk($tasks2, function(&$v) {
            $v['url'] = str_replace('{NUM}', $v['week'], $this->url);
            $v['name'] = 'CCTV5+';
        });

        return ArrayHelper::merge($tasks, $tasks2);
    }
}