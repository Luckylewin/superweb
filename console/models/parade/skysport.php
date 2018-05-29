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


class skysport extends CommonParade implements collector
{
    public $debug = false;
    public $url = 'http://www.skysports.com/watch/tv-guide/';

    public function start()
    {
        $this->getSkySport();
    }

    public function getSkySport()
    {
        //获取这个星期的排表
        $tasks= $this->_getUrlGroup();

        foreach ($tasks as $task) {
            echo "获取" . $task['date'] . "的数据\n";
            $this->_getOneDay($task['date'], $task['url']);
        }

    }

    /**
     * 获取一天的数据
     * @param $currentDay
     * @param $url
     * @return bool
     */
    private function _getOneDay($currentDay, $url)
    {
        $data = Yii::$app->cache->get('skysport-mon');
        if ($this->debug == false && $data == false) {
            $snnopy = MySnnopy::init();
            $snnopy->fetch($url);
            $data = $snnopy->results;
            Yii::$app->cache->set('skysport-mon', $data);
        }

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
        !empty($programMap) && array_walk($programMap ,function (&$v) {
            !empty($v['parade']) && array_walk($v['parade'], function(&$_v){
                $_v = preg_split('/\n/', $_v);
                array_walk($_v, function(&$temp_v) {
                    $temp_v = trim($temp_v);
                });
                $_v = array_values(array_filter($_v));
                $_v['parade_name'] = $_v[0];
                $_v[1] = explode(',', $_v[1]);
                $_v['parade_time'] = date('H:i', strtotime($_v[1][0]));
                unset($_v[0], $_v[1]);
            });
        });

        foreach ($programMap as $value) {
            //插入数据
            //查找频道是否存在
            $channel = OttChannel::findOne(['name' => $value['name']]);
            if (!is_null(Parade::findOne(['channel_name' => $value['name'], 'parade_date' => $currentDay])) || empty($value)) {
                echo "已经存在|没有预告";
                return false;
            }

            $parade = new Parade();
            $parade->parade_date = $currentDay;
            $parade->parade_data = json_encode($value['parade']);
            $parade->channel_name = $value['name'];
            if ($channel) {
                $parade->channel_id = $channel->ID;
            }
            $parade->save(false);
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
        $week = $this->getWeekTime();
        if (!empty($week)) {
           array_walk($week, function(&$v) {
                $v['url'] = $this->url . date('d-m-Y', $v['timestamp']);
           });
        }

        return $week;
    }
}