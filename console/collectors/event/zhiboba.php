<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/29
 * Time: 9:27
 */

namespace console\collectors\event;

use Symfony\Component\DomCrawler\Crawler;
use console\collectors\parade\collector;

//美国时间
class zhiboba extends common implements collector
{

    public $url = 'https://www.zhibo8.cc/';

    public function start()
    {
        $this->collect();
    }

    public function collect()
    {
        $events = $this->getPage();
        $this->FIFA($events);
    }

    public function getPage()
    {
        $dom = $this->getDom($this->url);
        $events = [];
        $dom->filter(".schedule_container .box")->each(function(Crawler $box) use(&$events) {
            // 获取时间
            $time = $box->filter('.titlebar')->each(function(Crawler $h) {
                 $time = $h->text();
                 $time = explode(' ', trim($time));
                 return trim($time[0]);
            });

            $date = current($time);

            $box->filter('ul li')->each(function(Crawler $li) use(&$events, $date) {
                $events[] = [
                    'label' => $li->attr('label'),
                    'text' => $li->text(),
                    'date' => date('Y-') . str_replace(['月','日'],['-',''], $date)
                ];
            });
        });

        foreach ($events as &$event) {
            //找 cctv5 qq的位置
            $event['label'] = explode(',', $event['label']);
            if (preg_match('/等待更新|CCTV|QQ/', $event['text'])) {
                $event['text'] = preg_replace('/等待更新.*|CCTV.*|QQ.*/','', $event['text']);
            }

            $event['text'] = str_replace('-', '', $event['text']);
            $info = array_values(array_filter(explode(' ', $event['text'])));
            $event['info'] = $info;
        }

        return $events;
    }



    /**
     * 世界杯
     * @param $data
     */
    public function FIFA($data)
    {
        foreach ($data as $val) {
            if (in_array('世界杯', $val['label'])) {
                $raceName = $val['info'][1];
                $teams = [
                           'teamA' => $val['info'][2],
                           'teamB' => $val['info'][3]
                         ];
                $date = $val['date'] . ' ' . $val['info'][0] . ":00";
                $time = $this->convertTimeZone($date, 'timestamp', '0','8');

                $this->createMajorEvent("国际足联世界杯",$raceName, $time, $teams);

            }
        }

    }

    /**
     * 访问url数组
     * @return array
     */
    public function _getUrlGroup()
    {

    }

}