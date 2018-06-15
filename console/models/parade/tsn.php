<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/28
 * Time: 18:16
 */

namespace console\models\parade;

use console\components\MySnnopy;
use yii\helpers\Json;

class tsn extends CommonParade implements collector
{
    //https://www.tsn.ca/live/schedule 需要确认服务器影响
    public $url = 'https://capi.9c9media.com/destinations/tsn_web/platforms/desktop/channelAffiliates/';

    public function start()
    {
        $this->getTsn();
    }
    
    /**
     *
     */
    public function getTsn()
    {
        $tasks = $this->_getUrlGroup();

        if (empty($tasks)) {
            echo "SKIP: 数据库中存在所有来源为" . $this->getClassName(__CLASS__) . "的节目" , PHP_EOL;
            return;
        }

        foreach ($tasks as $task) {
            $url = $task['url'];
            $name = $task['name'];
            $currentDay = $task['date'];

            echo "抓取" . $name . " " .$currentDay . '的数据' , PHP_EOL;
            sleep(mt_rand(1,3));

            $snnopy = MySnnopy::init();
            $snnopy->fetch($url);
            $data = $snnopy->results;

            if (empty($data)) {
                throw new \Exception("抓取数据为空");
            }

            $data = Json::decode($data);
            $data = $data['Items'];
            $paradeData = [];
            foreach ($data as $value) {
                preg_match('/(?<=T)[^-]+/', $value['StartTime'], $preg);
                if (empty($preg)) continue;

                $parade_timestamp = $currentDay . ' ' . $preg[0];
                $paradeData[] = [
                    'parade_name' => $value['Desc'],
                    'parade_time' => $preg[0],
                    'parade_timestamp' => $this->convertTimeZone($parade_timestamp, 'timestamp',0, 8)
                ];
            }

            $this->createParade($name, $currentDay, $paradeData, __CLASS__, $url);
        }

    }

    public function _getUrlGroup()
    {
        //https://capi.9c9media.com/destinations/tsn_web/platforms/desktop/channelAffiliates/tsn1/schedules?StartTime=2018-06-02&EndTime=2018-06-03
        $programs = ['tsn1', 'tsn2', 'tsn3', 'tsn4', 'tsn5'];
        $week = $this->getWeekTime();
        $tasks = [];

        foreach ($programs as $program) {
            if (!empty($week)) {
                array_walk($week, function(&$v) use($program, &$tasks) {
                    $v['url'] = $this->url . $program . "/schedules?" . "StartTime=". $v['date'] . "&EndTime=" . date('Y-m-d', strtotime('+1day', $v['timestamp']));
                    $v['name'] = $program;
                    $tasks[] = $v;
                });
            }
        }

        $tasks = $this->getFinalTasks($tasks, $this->getClassName(__CLASS__), 'source');

        return $tasks;
    }
}