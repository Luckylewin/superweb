<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/6/20
 * Time: 15:58
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;
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

    public function actionHelp()
    {
        echo "php yii log/analyse : 统计收集日志数据 " , PHP_EOL;
    }

    //日志分析
    public function actionAnalyse()
    {
        //读取redis
        $this->redis = Yii::$app->redis;
        $this->redis->select($this->db);

        //每次获取长度
        $length = $this->redis->llen('log');

        while ($length) {
            $this->deal();
            $length--;
        }
    }

    /**
     * 处理日志统计
     */
    public function deal()
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
            $uid = $data['uid'];
            $header = $data['header'];
            $requestData = $data['data'];

            if (empty($header)) return false;

            // 按用户进行统计
            if ($uid) {
                $key = date('m-d:') . $uid;
                //$this->hincyby($key, $header);
            }

            // 接口按小时进行统计
            $key = date('m-d:H');
            $this->hincyby($key, $header);

            // 按小时进行统计(全部)
            $key = date('m-d:') . 'all';
            $this->hincyby($key, date('H'));

            // 接口按天数进行统计
            $key = date('m-d');
            $this->hincyby($key, $header);

            // 按节目进行统计
            if ($program) {
                $key = date('m-d:program');
                $this->hincyby($key, $program);
            }

        }
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