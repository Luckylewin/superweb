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
    public $url = 'https://www.sportsnet.ca/wp-content/themes/sportsnet/broadcast_schedule/ajax/broadcast_schedule/date_files/ONTARIO_';//20180531.xml?r=0.254844711544516042
    public $debug = true;

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
        $data = Yii::$app->cache->get('sport');
        if ($data == false && $this->debug) {
            $snnopy = MySnnopy::init();
            $snnopy->fetch($url);
            $data = $snnopy->results;
            Yii::$app->cache->set('sport', $data);
        }

        $dom = new Crawler();
        $dom->addXmlContent($data);

        $dom->filter('channels channel')->each(function(Crawler $node) use($date) {
            $paradeData = [
                'name' => $node->attr('id'),
                'parade' => []
            ];
            $node->filter('program')->each(function(Crawler $programNode) use(&$paradeData, $date) {
                $paradeData['parade'][] = [
                    'parade_name' => $programNode->filter('SeriesName')->text(),
                    'parade_time' => $programNode->attr('startTime')
                ];
            });

            if (!empty($paradeData['parade'])) {
                $this->createParade($paradeData['name'], $date ,$paradeData['parade']);
            }
        });
    }

    public function _getUrlGroup()
    {
        $tasks = $this->getFutureTime(6, 'Ymd');
        array_walk($tasks, function(&$v) {
            $v['url'] = $this->url . $v['param'] . ".xml?r=0.". mt_rand(111111111111111111,999999999999999999)  ;
        });
        return $tasks;
    }
}