<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/29
 * Time: 9:27
 */

namespace console\models\parade;

use Symfony\Component\DomCrawler\Crawler;
use Yii;
use console\components\MySnnopy;

class espn extends CommonParade implements collector
{
    public $url = 'http://espn.go.com/espntv/onair/index?start=';

    public function start()
    {
        $this->getEspn();
    }

    public function getEspn()
    {
        $snnopy = MySnnopy::init();
        $data = Yii::$app->cache->get('espn');
        if ($data == false) {
            $snnopy->fetch('http://www.espn.com/espntv/onair/index?start=6-04-18-7:00-PM');
            Yii::$app->cache->set('espn', $snnopy->results);
            $data = $snnopy->results;
        }

        $program = ['espn', 'espn2', 'espnews', 'classic', 'deportes', 'espnu', 'espn3', 'longhorn'];
        $time = ['10:00', '10:30', '11:00', '11:30'];

        $dom = new Crawler();
        $dom->addHtmlContent($data);

        $dom->filter('.listings-grid .row ul')->each(function (Crawler $node, $i) use($program, $time) {
             //判断时间
             $num = $node->filter('.divide-line')->count();
             $node->filter('li')->each(function(Crawler $liDom){
                 echo $liDom->attr('class');
             });

             echo PHP_EOL;
        });


    }

    public function _getUrlGroup()
    {
        $this->getWeekTime();
        $clocks = [
            ['time_start' => '00:00','params' => '9:00-PM'],
            ['time_start' => '02:00','params' => '11:00-PM'],
            ['time_start' => '04:00','params' => '1:00-AM'],
            ['time_start' => '06:00','params' => '3:00-AM'],
            ['time_start' => '08:00','params' => '5:00-AM'],
            ['time_start' => '10:00','params' => '7:00-AM'],
            ['time_start' => '12:00','params' => '9:00-AM'],
            ['time_start' => '14:00','params' => '11:00-AM'],
            ['time_start' => '16:00','params' => '1:00-PM'],
            ['time_start' => '18:00','params' => '3:00-PM'],
            ['time_start' => '20:00','params' => '5:00-PM'],
            ['time_start' => '22:00','params' => '7:00-PM']
        ];

        $urlGroup = [];
        $dates = $this->getFutureTime(6);
        foreach ($dates as $date) {
             foreach ($clocks as $clock) {
                 $urlGroup[] = [
                      'date' => $date['date'],
                      'url' => $this->url . ltrim($date['param'], '0') . '-' . $clock['params'],
                      'start' => $clock['time_start'],
                 ];
             }
        }

        print_r($urlGroup);

    }
}