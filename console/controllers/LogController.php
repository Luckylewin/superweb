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

/**
 * 日志分析控制器
 * Class LogController
 * @package console\controllers
 */
class LogController extends Controller
{
    public $db = 14;

    //日志分析
    public function actionAnalyse()
    {
        //读取redis
        $redis = Yii::$app->redis;
        $redis->select($this->db);

        //每次获取长度
        $length = $redis->llen('log');

        while ($length) {
            $this->deal($redis);
            $length--;
        }
    }

    /**
     * 处理日志统计
     * @param $redis yii\redis\Connection
     */
    public function deal($redis)
    {
        $log = $redis->lpop('log');

        if ($log) {
            $log = explode('|', $log);
            list($time, $ip, $json, $error) = $log;
            $data = json_decode($json, true);
            $program = isset($data['class']) ? $data['class'] : false;
            $uid = $data['uid'];
            $header = $data['header'];
            $requestData = $data['data'];

            // 按用户进行统计
            $isExist = (boolean)$redis->exists($uid);
            $key = date('m/d:') . $uid;

            if ($isExist === false) {
                $redis->hmset($key, $header, 1);
                $redis->expire($key, 86400 * 2);
            } else {
                $redis->hincrby($key, $header, 1);
            }

            // 按小时进行统计
            $key = date('m/d:H');
            $isExist = (boolean)$redis->exists($key);
            if ($isExist === false) {
                $redis->hmset($key, $header, 1);
                $redis->expire($key, 86400 * 2);
            } else {
                $redis->hincrby($key, $header, 1);
            }

            // 按天数进行统计
            $key = date('m/d');
            $isExist = (boolean)$redis->exists($key);
            if ($isExist === false) {
                $redis->hmset($key, $header, 1);
                $redis->expire($key, 86400 * 2);
            } else {
                $redis->hincrby($key, $header, 1);
            }

            // 按节目进行统计
            if ($program) {
                $key = date('m/d:program');
                $isExist = (boolean)$redis->exists($key);
                if ($isExist === false) {
                    $redis->hmset($key, $program, 1);
                    $redis->expire($key, 86400 * 2);
                } else {
                    $redis->hincrby($key, $program, 1);
                }
            }

        }
    }


}