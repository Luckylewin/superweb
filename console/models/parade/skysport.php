<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/28
 * Time: 15:44
 */

namespace console\models\parade;

use backend\models\Parade;
use common\models\OttChannel;
use Symfony\Component\DomCrawler\Crawler;
use Yii;
use console\components\MySnnopy;


//英国时间
class skysport extends CommonParade implements collector
{
    //确认来访者的影响
    public $debug = false;
    public $url = 'http://www.skysports.com/watch/tv-guide/';

    public function start()
    {
        $this->getSkySport();
    }

    public function getSkySport()
    {
        //获取抓取任务时间排表
        $tasks = $this->_getUrlGroup();

        if (empty($tasks)) {
            echo "SKIP: 数据库中存在所有来源为" . $this->getClassName(__CLASS__) . "的节目" , PHP_EOL;
            return false;
        }

        foreach ($tasks as $task) {
            echo "获取" . $task['date'] . "的数据\n";
            $this->_getOneDay($task['date'], $task['url']);
        }

        return true;
    }

    /**
     * 获取一天的数据
     * @param $currentDay
     * @param $url
     * @return bool
     */
    private function _getOneDay($currentDay, $url)
    {
        $snnopy = MySnnopy::init();
        $snnopy->fetch($url);
        $data = $snnopy->results;

        $dom = new Crawler();
        $dom->addHtmlContent($data, 'UTF-8');

        //获取节目名称数组
        $programMap = [];
        $dom->filter('.tvg-channels')->filter('.ss-tvlogo > img')->each(function(Crawler $node) use(&$programMap) {
            $programMap[] = ['name' => trim($node->attr('alt'))];
        });

        //获取节目预告信息
        $dom->filter('.tvg-wrap .row-table')->each(function(Crawler $node, $i) use(&$programMap){
            if ($i > 0 ) {
                $programMap[$i-1]['parade'] = $node->filter(' .tvg-block')->each(function (Crawler $block) {
                    return trim($block->text());
                });
            }
        });

        //整理数据
        !empty($programMap) && array_walk($programMap ,function (&$v) use ($currentDay) {
            !empty($v['parade']) && array_walk($v['parade'], function(&$_v, $k) use($currentDay){
                $_v = preg_split('/\n/', $_v);

                array_walk($_v, function(&$temp_v) {
                    $temp_v = trim($temp_v);
                });

                $_v = array_values(array_filter($_v));
                $_v['parade_name'] = $_v[0];
                $_v[1] = explode(',', $_v[1]);
                $_v['parade_time'] = date('H:i', strtotime($_v[1][0]));

                //标记跨度从昨天到今天的节目
                if ($k ==0 && strpos($_v[1][0], 'pm') !== false) {
                    $_v['is_valid'] = false;
                } else {
                    $_v['is_valid'] = true;
                }

                unset($_v[0], $_v[1]);
            });
        });

        foreach ($programMap as $value) {
            array_walk($value['parade'] ,function(&$v) use($currentDay) {

                if ($v['is_valid']) {
                    $v['parade_timestamp'] = $this->convertTimeZone($currentDay . ' ' . $v['parade_time'], 'timestamp', 0, 8);
                } else {
                    $v['parade_timestamp'] = $this->convertTimeZone(date('Y-m-d', strtotime($currentDay) - 86400) . ' ' . $v['parade_time'], 'timestamp', 0, 8);
                }

                unset($v['is_valid']);
            });

            $this->createParade($value['name'], $currentDay, $value['parade'], __CLASS__, $url);
        }

        sleep(mt_rand(2,4));
    }

    /**
     * 获取访问的链接
     * @return array
     */
    private function _getUrlGroup()
    {
        // ① 获得这周周一的时间戳
        $week = $this->getFutureTime(6, 'Y-m-d');
        if (!empty($week)) {
           array_walk($week, function(&$v) {
                $v['url'] = $this->url . date('d-m-Y', $v['timestamp']);
           });
        }

        $week = $this->getFinalTasks($week, $this->getClassName(__CLASS__), 'source');

        return $week;
    }
}