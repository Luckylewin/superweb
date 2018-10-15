<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/31
 * Time: 17:14
 */

namespace console\collectors\parade;


use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class manmankan extends CommonParade implements collector
{
    public $url = 'http://www.manmankan.com/dy2013/jiemubiao/12/{pinyin}.shtml';//zhouwu
    public $url2 = 'http://www.manmankan.com/dy2013/jiemubiao/25/{pinyin}.shtml';

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
        $dom = $this->getDom($url, 'html', 'gb2312');
        $paradeData = [];
        $dom->filter('.shijian li')->each(function(Crawler $node) use(&$paradeData, $date) {
            if ($node->filter('em')->count()) {
                $paradeData[] = [
                    'parade_time' => $node->filter('em')->text(),
                    'parade_name' => $node->filter('span')->text(),
                    'parade_timestamp' => $this->convertTimeZone($date . ' '. $node->filter('em')->text(), 'timestamp', 0, 8)
                ];
            }
        });

       $this->createParade($name, $date, $paradeData, __CLASS__, $url);
    }

    public function getUrlGroup()
    {
        $week = ['1'=>'zhouyi', '2' => 'zhouer', '3' => 'zhousan', '4' => 'zhousi', '5' => 'zhouwu', '6' => 'zhouliu', '7' => 'zhouri'];

        $tasks = $this->getWeekTime();
        array_walk($tasks, function(&$v) use($week) {
            $v['name'] = 'cctv5';
            $v['param'] = $week[$v['week']];
            $v['url'] = str_replace('{pinyin}', $v['param'], $this->url);
        });

        $tasks2 = $this->getWeekTime();
        array_walk($tasks2, function(&$v) use($week) {
            $v['name'] = 'cctv5+';
            $v['param'] = $week[$v['week']];
            $v['url'] = str_replace('{pinyin}', $v['param'], $this->url2);
        });

        return ArrayHelper::merge($tasks, $tasks2);
    }
}