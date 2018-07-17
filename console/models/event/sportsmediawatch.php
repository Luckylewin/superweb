<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/12
 * Time: 14:33
 */

namespace console\models\event;
use common\components\BaiduTranslator;
use console\models\parade\collector;
use Symfony\Component\DomCrawler\Crawler;

//0时区时间
class sportsmediawatch extends common implements collector
{
    public $url = 'http://www.sportsmediawatch.com/nba-tv-schedule/';

    public function start()
    {
        $this->collect();
    }

    public function collect()
    {
       $this->getPage();
    }

    private function OFFSEASON()
    {

    }

    public function getPage()
    {
        $dom = $this->getDom($this->url);
        $data = [];

        $dom->filter('table')->each(function(Crawler $table) use(&$data) {
            $th = $table->filter('tr')->eq(0)->filter('th')->eq(0)->text();
            if (trim($th) == 'OFFSEASON EVENTS') {
                $date = '';

                $table->filter('tr')->each(function(Crawler $node) use(&$date, &$data) {

                    if ($node->filter('th')->count()) {
                        $timeText = $node->filter('th')->eq(0)->text();
                        $date = date('Y-m-d', strtotime(trim($timeText)));
                    } else {
                        $td = $node->filter('td');
                        if ($td->count() >= 3) {
                            $time = $td->eq(0)->text();
                            $datetime = $date . ' ' . $time;
                            $time = $this->convertTimeZone($datetime, 'timestamp', 8, -4);
                            $teamsAll = $td->eq(1)->text();
                            $teamsSpan = $td->eq(1)->filter('span')->text();
                            $teams = str_replace($teamsSpan, '', $teamsAll);

                            $channel_name = $td->eq(2)->text();


                            if (strpos($teams, '-') !== false) {
                                $teams = explode('-', $teams);
                                $teams = [
                                    'teamA' => $teams[0],
                                    'teamB' => $teams[1]
                                ];

                                $data[] = [
                                    'time' => $time,
                                    'teams' => $teams,
                                    'channel' => $channel_name
                                ];
                            } else if(!empty($teams)) {
                                $data[] = [
                                    'time' => $time,
                                    'teams' => [],
                                    'channel' => $channel_name,
                                    'event_name' => $teams
                                ];
                            }
                        }
                    }
                });
            }


            if (!empty($data)) {
                foreach ($data as $val) {
                    try {
                        if (isset($val['event_name'])) {
                            $majorEvent = $this->createMajorEvent("NBA夏季联赛", 'NBA夏季联赛' , $val['time'], $val['teams']);
                        } else {
                            $majorEvent = $this->createMajorEvent("NBA夏季联赛", BaiduTranslator::translate($val['event_name'], 'en', 'zh') , $val['time'], $val['teams']);
                        }
                        
                        if ($majorEvent) {
                            $majorEvent->bindChannel($val['channel']);
                        }
                    }catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }


        });

        return $data;

    }
}