<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/28
 * Time: 18:16
 */

namespace console\models\parade;


use backend\models\Parade;
use common\models\OttChannel;
use console\components\MySnnopy;
use yii\helpers\Json;

class tsn extends CommonParade implements collector
{
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
                $paradeData[] = [
                    'parade_name' => $value['Desc'],
                    'parade_time' => $preg[0]
                ];
            }


            //查找频道是否存在
            $channel = OttChannel::findOne(['name' => $name]);
            if (!is_null(Parade::findOne(['channel_name' => $name, 'parade_date' => $currentDay])) || empty($paradeData)) {
                echo "已经存在|没有预告",PHP_EOL;
                continue;
            }

            $parade = new Parade();
            $parade->parade_date = $currentDay;
            $parade->parade_data = json_encode($paradeData);
            $parade->channel_name = $name;
            if ($channel) {
                $parade->channel_id = $channel->id;
            }
            $parade->save(false);
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

        return $tasks;
    }
}