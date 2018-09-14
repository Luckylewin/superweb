<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/6/20
 * Time: 15:58
 */

namespace console\controllers;

use backend\models\LogInterface;
use backend\models\ProgramLog;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\redis\Connection;

/**
 * 日志分析控制器
 * Class LogController
 * @package console\controllers
 */
class LogController extends Controller
{
    public $db = 14;
    /**
     * @var Connection
     */
    public $redis;

    public $interfaces = ['watch', 'total', 'notify', 'getCountryList', 'getOttRecommend', 'getMajorEvent', 'register', 'ottCharge', 'getNewApp', 'renew', 'getAppMarket', 'getIptvList', 'getOttNewList', 'getClientToken'];

    public function actionHelp()
    {
        $this->stdout("php yii log/analyse : 实时统计接口日志数据" . PHP_EOL, Console::FG_YELLOW);
        $this->stdout("php yii log/daily-log : 统计每日接口日志数据" . PHP_EOL, Console::FG_YELLOW);
    }

    //日志实时统计（单独开进程运行）
    public function actionAnalyse()
    {
        //读取redis
        $this->redis = Yii::$app->redis;
        $this->redis->select($this->db);

        while (true) {
            //每次获取长度
            $length = $this->redis->llen('log');

            while ($length) {
                $this->deal();
                $length--;
            }

            sleep(15);
        }

    }


    /**
     * 实时处理日志统计
     */
    private function deal()
    {
        $log = $this->redis->lpop('log');

        if ($log) {
            $log = explode('|', $log);

            $time = isset($log[0]) ? $log[0] : false;
            $ip = isset($log[1]) ? $log[1] : false;
            $json = isset($log[2]) ? $log[2] : '';
            $error = isset($log[3])? $log[3] : false;

            $data = json_decode($json, true);

            $program = isset($data['class']) ? $data['class'] : false;
            $uid = isset($data['uid']) ? $data['uid'] : false;
            $header = isset($data['header']) ? $data['header'] : false;
            $name = isset($data['name']) ? $data['name'] : false;
            $requestData = isset($data['data']) ? $data['data'] : false;

            if (empty($header)) return false;
            
            if ($program == false) {
                if (in_array($header, ['tvnet','viettel','sohatv','thvl','hoabinhtv','v4live','migu','sohulive','hplus','newmigu','haoqu','tencent','vtv','ott','local'])) {
                    $program = $header;
                    $header = false;
                }
            }

            // 按小时进行统计(全部)
            $key = "interface:" . date('m-d:') . 'hour';
            $this->hincyby($key, date('H'));
            $this->redis->expire($key, '96400');

            if (
                $header &&
                in_array($header, $this->interfaces)
            ) {

                // 全部接口按小时进行统计
                $key = "interface:" . date('m-d:H');
                $this->hincyby($key, $header);
                $this->redis->expire($key, '96400');

                // 接口按天数进行统计
                $key = "interface:" . date('m-d:') . "day";
                $this->hincyby($key, $header);
                $this->redis->expire($key, '96400');
            }

            // 按节目进行统计
            if ($program) {

                if ($name) {
                    $key = "program:" . date('m-d:') . 'day' ;
                    $this->hincyby($key, $name);
                    $this->redis->expire($key, '96400');
                }

                // 所有节目按小时统计
                $key = "program:" . date('m-d:') . 'hour';
                $this->hincyby($key, date('H'));
                $this->redis->expire($key, '96400');

                // 每个节目每天的观看次数
                $key = "program:" . date('m-d:') . 'set';
                $this->redis->hincrby($key, $name, 1);
                $this->redis->expire($key, '96400');
            }

        }
    }

    public function actionDailyLog()
    {
        // 统计每天的接口调用情况
        $this->redis = Yii::$app->redis;
        $this->redis->select($this->db);

        $this->setTodayProgramData();

        $todayData = $this->getTodayInterfaceData();
        $this->setInterfaceLog($todayData);
    }

    private function setTodayProgramData()
    {
        $key = "program:" . date('m-d:') . 'day';
        $data = $this->redis->hgetall($key);

        $data = $this->_map($data);
        arsort($data);
        
        $data = array_slice($data, 0, 20);

        $log = ProgramLog::find()->where(['date' => date('Y-m-d')])->one();
        if ($log == false) {
            $log = new ProgramLog();
            $log->date = date('Y-m-d');
        }


        $log->all_program_sum = $log->server_program = json_encode($data);
        $log->save(false);
    }

    private function getTodayInterfaceData()
    {
        $today = [];
        $hours = $this->_generateHour();

        $all_key = "interface:" . date('m-d:') . 'hour';
        $all_data = $this->redis->hgetall($all_key);
        $all_data = $this->_map($all_data);

        $program_key = "program:" . date('m-d:') . 'hour';
        $program_data = $this->redis->hgetall($program_key);
        $program_data = $this->_map($program_data);


        foreach ($hours as $hour) {
            $key = "interface:" . date('m-d:') . $hour;
            $data = $this->redis->hgetall($key);
            $today[] = array_merge(
                ['total' => isset($all_data[$hour]) ? $all_data[$hour] : 0],
                ['watch' => isset($program_data[$hour]) ? $program_data[$hour] : 0],
                $this->_map($data))
            ;
        }
        return $today;
    }

    private function setInterfaceLog($todayData)
    {
        // 查询是否已经存在今天的数据 有就更新
        $log = LogInterface::find()->where(['date' => date('Y-m-d')])->one();
        if (is_null($log)) {
            $log = new LogInterface();
        }

        $interfaces = $this->interfaces;
        foreach ($interfaces as $interface) {
            $log->$interface = $this->_fill(ArrayHelper::getColumn($todayData, $interface));
        }

        $log->year = date('Y');
        $log->date = date('Y-m-d');

        $log->save(false);

    }

    private function _fill($data)
    {
        array_walk($data, function(&$v, $k) {
            if (empty($v)) {
                $v = 0;
            }
        });

        return json_encode($data);
    }

    /**
     *
     */
    private function _generateHour()
    {
        $hours = [];
        for ($h = 0; $h <= 23; $h++) {
            if ($h < 10) {
                $h = sprintf('%02d', $h);
            }
            $hours[] =  $h;
        }

        return $hours;
    }

    private function _map($data)
    {
        $map = [];
        if (!empty($data)) {
            $len = count($data);
            for ($i = 0; $i < $len; $i+=2) {
                if (isset($data[$i + 1]) && !empty($data[$i + 1])) {
                    $map[$data[$i]] = $data[$i + 1];
                } else {
                    $map[$data[$i]] = 0;
                }
            }
        }

        return $map;
    }

    /**
     * @param $key
     * @param $field
     * @param int $increment
     */
    public function hincyby($key,$field ,$increment = 1)
    {
        $isExist = (boolean)$this->redis->exists($key);
        if ($isExist === false) {
            $this->redis->hmset($key, $field, $increment);
            $this->redis->expire($key, 86400 * 2);
        } else {
            $this->redis->hincrby($key, $field, 1);
        }
    }

}