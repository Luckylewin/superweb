<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/29
 * Time: 9:27
 */

namespace console\models\parade;

use Yii;
use Symfony\Component\DomCrawler\Crawler;
use console\components\MySnnopy;

//美国时间
class espn extends CommonParade implements collector
{
    //http://www.espn.com/espntv/onair/index?start=5-27-18-5:00-PM
    public $url = 'http://espn.go.com/espntv/onair/index?start=';
    public $debug = false;

    public function start()
    {
        $this->getEspn();
    }

    public function getEspn()
    {

        $tasks = $this->_getUrlGroup();

        foreach ($tasks as $date => $task) {
            $program = $this->initProgram();
            echo "采集{$date}预告", PHP_EOL;

            foreach ($task as $_task) {
                $this->getOnePage($program, $_task['start'], $_task['url'], $date);
                $this->_sleep(2,5);
            }

            foreach ($program as $_program) {
                $this->createParade($_program['name'], $date, $_program['parade'], __CLASS__, $task[0]['url']);
            }
            $this->_sleep(2, 5);
        }
    }

    /**
     * 初始化
     * @return array
     */
    private function initProgram()
    {
        $program = [
            ['name' => 'espn','parade'=>[]],
            ['name' => 'espn2','parade'=>[]],
            ['name' => 'espnews','parade'=>[]],
            ['name' => 'classic','parade'=>[]],
            ['name' => 'deportes','parade'=>[]],
            ['name' => 'espnu','parade'=>[]],
            ['name' => 'espn3','parade'=>[]],
            ['name' => 'longhorn','parade'=>[]]
        ];

        return $program;
    }

    /**
     * 访问一页
     * @param $program
     * @param $timeStart
     * @param $pageUrl
     */
    public function getOnePage(&$program,$timeStart, $pageUrl, $date)
    {
        $snnopy = MySnnopy::init();
        $data = Yii::$app->cache->get('espn');
        if ($data == false || $this->debug == false) {
            $snnopy->fetch($pageUrl);
            Yii::$app->cache->set('espn', $snnopy->results);
            $data = $snnopy->results;
        }

        $dom = new Crawler();
        $dom->addHtmlContent($data);
        date_default_timezone_set('America/New_York');

        $dom->filter('.listings-grid .row ul')->each(function (Crawler $node, $i) use(&$program, $timeStart, $date) {

            //判断时间
            $num = $node->filter('.divide-line')->count();
            $lengths = [];

            if (!isset($program[$i]['name']))   return;

            echo "节目" . $program[$i]['name'],PHP_EOL;

            $node->filter('li')->each(function(Crawler $liDom, $ii) use(&$program, &$timeStart, &$lengths, $i, $date) {
                $class = str_replace(['divide-line ', 'first'], ['', ''], $liDom->attr('class'));
                $class && array_push($lengths, $class);
                if ($liDom->filter('.cell-padding')->count()) {
                    //计算时间 求出开始时间

                    if ($class != 'length-8' && $ii > 1) {
                        $previousLength = array_shift($lengths);
                        $map = ['length-1' => '900','length-2' => '1800', 'length-3' => 2700, 'length-4'=>3600,'length-5' => 4500, 'length-6'=>5400, 'length-7' => 6300 ];
                        $timeStart = strtotime($timeStart) + $map[$previousLength];
                        $timeStart = date('H:i', $timeStart);
                    }

                    $parade = [
                        'parade_name' => $liDom->filterXPath('//div[@class="cell-padding"]')->text(),
                        'parade_time' => $timeStart,
                        'parade_timestamp' => strtotime($date . " " . $timeStart)
                    ];
                    $program[$i]['parade'][] = $parade;
                }
            });



            echo PHP_EOL;
        });
    }

    /**
     * 访问url数组
     * @return array
     */
    public function _getUrlGroup()
    {
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
                 $urlGroup[$date['date']][] = [
                      'date' => $date['date'],
                      'url' => $this->url . ltrim($date['param'], '0') . '-' . $clock['params'],
                      'start' => $clock['time_start'],
                 ];
             }
        }

        return $urlGroup;
    }


}