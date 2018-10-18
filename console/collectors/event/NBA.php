<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/8
 * Time: 11:12
 */

namespace console\collectors\event;

use console\collectors\parade\collector;

class NBA extends common implements collector
{

    public $url = 'https://www.zhibo8.cc/';

    public function start()
    {
        $this->getPage();
    }

    public function getPage()
    {
        $start = strtotime('today');
        $end = strtotime('2018-12-15');

        for ($i = $start; $i <= $end;) {
            $data = [];
            $date = date('Y-m-d', $i);
            $content = $this->getDom("http://tiyu.baidu.com/api/match/NBA/live/date/{$date}/direction/after?from=self");
            $content = json_decode($content->text(), true);

            foreach ($content['data'] as $list) {
                foreach ($list['list'] as $val) {

                    if ($val['matchName'] == 'NBA常规赛') {
                        $data[] = [
                            'time' => strtotime($val['startTime']),
                            'teams' => [
                                'teamA' => $val['leftLogo']['name'],
                                'teamB' => $val['rightLogo']['name']
                            ]
                        ];
                    }

                }
            }

            if (!empty($data)) {
                foreach ($data as $val) {
                    try {
                        $this->createMajorEvent("NBA常规赛", 'NBA常规赛' , $val['time'], $val['teams']);
                    }catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
            $i += 86400 * 2;
            sleep(2);
        }
    }
}