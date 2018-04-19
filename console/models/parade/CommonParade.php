<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:05
 */

namespace console\models\parade;

use backend\models\Parade;
use backend\components\MyRedis;

class CommonParade
{
    public $redis;

    public function __construct()
    {
        $this->redis = MyRedis::init(MyRedis::REDIS_PARADE_CONTENT);
    }

    protected function _clearOldData()
    {
        $this->redis->getRedis()->flushDB();
        $date = date('Y-m-d',strtotime('-2 day'));
        Parade::deleteAll("parade_date <= '$date'");
    }

    protected function _saveToRedis()
    {
        //获取数据库中的节目数
        $data = Parade::find()->select('channel_name')->groupBy('channel_name')->asArray()->all();

        //三个时间段的条件
        $conditions = array(
            "1" => " parade_date  = '".date('Y-m-d')."' "  ,//1天
            "3" => " parade_date >= '".date('Y-m-d',strtotime('yesterday')) ."' AND parade_date <= '".date('Y-m-d',strtotime('+1 day'))."' "  ,//三天
            "5" => " parade_date >= '".date('Y-m-d',strtotime('yesterday')) ."' AND parade_date <= '".date('Y-m-d',strtotime('+3 day'))."' "  ,//五天
            "7" => " parade_date >= '".date('Y-m-d',strtotime('yesterday')) ."' AND parade_date <= '".date('Y-m-d',strtotime('+6 day'))."' "  ,//七天
        );

        $totalCache = 0;

        foreach ($data as  $channel) {
            $channelName = $channel['channel_name'];

            $atLastOne = false;
            foreach ($conditions as $dayNum => $where) {
                //查一下有没有这么多天
                $dbData = Parade::find()
                        ->select('parade_date')
                        ->where("channel_name='{$channelName}'")
                        ->groupBy('parade_date')
                        ->asArray()
                        ->all();

                if (count($dbData) < $dayNum) {
                    //echo "{$channelName}不足够生成{$dayNum}天 的数据缓存".PHP_EOL;
                    continue;
                }

                $dbData = Parade::find()
                    ->select('channel_name,parade_date,parade_data')
                    ->where("channel_name='{$channelName}'")
                    ->andWhere($where)
                    ->asArray()
                    ->all();

                $this->_insertRedis($dayNum,$channelName,$dbData);
                $atLastOne = true;
                echo "{$channelName}生成{$dayNum}天 的数据缓存".PHP_EOL;
            }
            if ($atLastOne) {
                $totalCache++;
            }
        }
        echo PHP_EOL,"本次任务总计生成 ",$totalCache,' 个频道的预告缓存',PHP_EOL;
    }

    /**
     *
     */
    protected function completeYesterday($data)
    {
        if (!empty($data)) {
            $yesterday = date('Y-m-d',strtotime('yesterday'));
            $flag = false;
            foreach ($data as $t) {
                if ($t['parade_date'] == $yesterday) {
                    $flag = true;
                }
            }
            if ($flag == false) {
                array_unshift($data,array(
                    'channel_name' => $data[0]['channel_name'],
                    'parade_date' => $yesterday,
                    'parade_data' => '{}'
                ));
            }
            return $data;
        }
        return false;
    }

    protected function _insertRedis($dayNum,$channel,$paradeData)
    {
        $data = array();
        if (empty($paradeData)) {
            return false;
        }

        $paradeData = $this->completeYesterday($paradeData);

        $timeIndex = ['Today','Tomorrow-1','Tomorrow-2','Tomorrow-3','Tomorrow-4','Tomorrow-5','Tomorrow-6'];
        if (!empty($paradeData) && is_array($paradeData)) {
            foreach ($paradeData as $key => $value) {
                $diff = (strtotime($value['parade_date']) - strtotime('today'))/86400;
                $index = $diff < 0?'Yesterday':$timeIndex[$diff];
                $parade = json_decode($value['parade_data'],true);
                $epg = array();
                foreach ($parade as $_parade) {
                    $epg[] = array('time'=>substr($_parade['parade_time'],0,5),'name'=>$_parade['parade_name']);
                }
                $data[$index] = array_values($epg);

            }
        }

        if (empty($data['Tomorrow-1'])) {
            $data['Tomorrow-1'] = [];
        }

        $hash = json_encode(array(
            'channel' => $channel,
            'day' => $dayNum,
            'parade' => $data
        ));

        $key = str_replace(' ','_', $channel) . '_'.date('Ymd') . "_" .$dayNum;
        return $this->redis->set($key,$hash);
    }

    public function _sleep($min,$max)
    {
        sleep(mt_rand($min,$max));
    }
}