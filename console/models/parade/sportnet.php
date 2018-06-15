<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/30
 * Time: 17:58
 */
namespace console\models\parade;

use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use Yii;
use Snoopy\Snoopy;

class sportnet extends CommonParade implements collector
{
    //西五区时间

    public $url = 'https://www.sportsnet.ca/wp-content/themes/sportsnet/broadcast_schedule/ajax/broadcast_schedule/date_files/ONTARIO_';//20180531.xml?r=0.254844711544516042
    public $data = [];

    public function start()
    {
        $this->getSportNet();
    }

    public function getSportNet()
    {
        $tasks = $this->_getUrlGroup();
        foreach ($tasks as $task) {
            $this->getOneDay($task['date'], $task['url']);
            $this->_sleep(3,10);
        }
    }

    public function getOneDay($date, $url)
    {
        echo $url,PHP_EOL;
        $snnopy = MySnnopy::init();
        $snnopy->fetch($url);
        $data = $snnopy->results;

        date_default_timezone_set('America/New_York');

        $dom = new Crawler();
        $dom->addXmlContent($data);

        $dom->filter('channels channel')->each(function(Crawler $node) use($date, $url) {
            $paradeData = [
                'name' => $node->attr('id'),
                'parade' => []
            ];
            $node->filter('program')->each(function(Crawler $programNode, $index) use(&$paradeData, $date) {
                $startTime = $programNode->attr('startTime');
                //判断时间是否填充了昨天的时间
                if (strstr($startTime, ':', true) > 15 && $index < 7) {
                    //时间减去一天
                    $parade_datetime = date('Y-m-d', strtotime('-1 day', strtotime($date))) ." ". $startTime;
                } else {
                    $parade_datetime = $date ." ". $startTime;
                }

                $paradeData['parade'][] = [
                    'parade_name' => $programNode->filter('SeriesName')->text(),
                    'parade_time' => $programNode->attr('startTime'),
                    'parade_timestamp' => $this->convertTimeZone($parade_datetime, 'timestamp', 0, -5 )
                ];
            });

            if (!empty($paradeData['parade'])) {
                $this->createParade($paradeData['name'], $date ,$paradeData['parade'], __CLASS__, $url);
            }
        });
    }


    public function _getUrlGroup()
    {
        $tasks = $this->getFutureTime(6, 'Ymd');

        array_walk($tasks, function(&$v) {
            $v['url'] = $this->url . $v['param'] . ".xml?r=0.". mt_rand(111111111111111111,999999999999999999)  ;
        });

        $tasks = $this->getFinalTasks($tasks, $this->getClassName(__CLASS__), 'source');

        return $tasks;
    }
}