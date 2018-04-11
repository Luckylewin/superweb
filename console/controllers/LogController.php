<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/11
 * Time: 16:35
 */

namespace console\controllers;

use Yii;
use backend\components\MySSH;
use backend\models\AppLog;
use backend\models\Mac;
use backend\models\ProgramLog;
use backend\models\TimelineLog;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * 日志处理类
 * Class paradeTimer
 * @package Applications\YourApp\timer
 */
class LogController extends Controller
{
    protected $path = '/var/www/log/app/';
    protected $yesterdayLog;

    public function actionIndex()
    {
        $this->yesterdayLog = $this->getYesterdayLog();

        if (file_exists($this->yesterdayLog)) {
            $start = memory_get_usage(true);
            $this->analyseUserBehavior($this->yesterdayLog);
            $this->analyseInterface($this->yesterdayLog);
            $this->analyseProgram($this->yesterdayLog);
            $this->getSSH()->close();
            $end = memory_get_usage(true);
            $usage = $end - $start;
            echo "内存消耗 {$usage}\n";
        }
        return ExitCode::OK;
    }



    public function analyseProgram($yesterdayLog)
    {
        //统计本地解析 前二十个节目播放数据
        $shell = "cat $yesterdayLog | grep local | awk -F '|' '{print $9}' | sort -nr | uniq -c | sort -k1 -nr";
        $all = $this->getSSH()->exec($shell);
        $all = array_filter(preg_split('/[\r\n]+/s', $all));

        $local_statics = [];

        if (!empty($all)) {
            foreach ($all as $k => $item) {
                if ($k > 20) {
                    continue;
                }
                $item = explode(' ', trim($item));
                $name = $item[1];
                $num = $item[0];
                $local_statics[$name] = $num;
            }
        }

        //统计服务器端
        $shell = "cat $yesterdayLog | grep -E 'tvnet|viettel|sohatv|thvl|hoabinhtv|v4live|migu|sohulive|hplus|newmigu|haoqu|tencent|vtv|ott' | awk -F '|' '{print $9}' | sort -nr | uniq -c | sort -k1 -nr";
        $all = $this->getSSH()->exec($shell);
        $all = array_filter(preg_split('/[\r\n]+/s', $all));

        $server_statics = [];
        if (!empty($all)) {
            foreach ($all as $k => $item) {
                if ($k > 20) {
                    continue;
                }
                $item = explode(' ', trim($item));
                $name = $item[1];
                $num = $item[0];
                $server_statics[$name] = trim($num);
            }
        }

        //统计全部
        $shell = "cat $yesterdayLog | grep -E 'tvnet|viettel|sohatv|thvl|hoabinhtv|v4live|migu|sohulive|hplus|newmigu|haoqu|tencent|vtv|ott|local' | awk -F '|' '{print $9}' | sort -nr | uniq -c | sort -k1 -nr";
        $all = $this->getSSH()->exec($shell);
        $all = array_filter(preg_split('/[\r\n]+/s', $all));

        $all_statics = [];
        if (!empty($all)) {
            foreach ($all as $k => $item) {
                $item = explode(' ', trim($item));
                $name = $item[1];
                $num = $item[0];
                $all_statics[$name] = trim($num);
            }
        }

        //统计全部节目数字
        $shell = "cat $yesterdayLog | grep -E 'tvnet|viettel|sohatv|thvl|hoabinhtv|v4live|migu|sohulive|hplus|newmigu|haoqu|tencent|vtv|ott|local' | wc -l";
        $sum = $this->getSSH()->exec($shell);

        $programLog = new ProgramLog();
        $programLog->local_program = json_encode($local_statics);
        $programLog->server_program = json_encode($server_statics);
        $programLog->all_program = json_encode($all_statics);
        $programLog->all_program_sum = trim($sum);
        $programLog->date = date('Y-m-d', $this->_setDate());
        $programLog->save(false);

    }


    /**
     * 按时间维度 查看日志
     * @param $yesterdayLog
     */
    public function analyseInterface($yesterdayLog)
    {

        $time = $this->getSSH()->exec("cat $yesterdayLog | awk -F '|' '{print $3 }' | awk -F ':' '{print $1}' | sort -u");
        $times = array_filter(preg_split('/[;\r\n]+/s', $time));

        $totalRequest = [];
        $watchRequest = [];
        $tokenRequest = [];
        $localRequest = [];
        $serverRequest = [];

        for ($i = 0; $i <= 23; $i++) {
            if (in_array($i, $times)) {
                $time = sprintf("%02d", $i);
                $shell = "cat $yesterdayLog | grep 'time|{$time}' | wc -l";
                $totalRequest[$i] = trim($this->getSSH()->exec($shell));

                $watch_shell = "cat $yesterdayLog | grep 'time|{$time}' | grep -E 'tvnet|viettel|sohatv|thvl|hoabinhtv|v4live|migu|sohulive|hplus|newmigu|haoqu|tencent|vtv|ott|local' | wc -l";
                $watch = $this->getSSH()->exec($watch_shell);
                $watchRequest[] = trim($watch);

                $local_shell = "cat $yesterdayLog | grep 'time|{$time}' | grep -E 'local' | wc -l";
                $local = $this->getSSH()->exec($local_shell);
                //本地解析
                $localRequest[] = trim($local);

                //服务器解析
                $serverRequest[] = end($watchRequest) - end($localRequest);

                $token_shell = "cat $yesterdayLog | grep 'time|{$time}' | grep 'getClientToken' | wc -l";
                $watch = $this->getSSH()->exec($token_shell);
                $tokenRequest[] = trim($watch);

            } else {
                $localRequest[] = $serverRequest[] = $tokenRequest[] = $watchRequest[] = $totalRequest[] = 0;
            }
        }

        $timeLineLog = new TimelineLog();
        $timeLineLog->year = date('Y');
        $timeLineLog->total_line = json_encode($totalRequest);
        $timeLineLog->watch_line = json_encode($watchRequest);
        $timeLineLog->token_line = json_encode($tokenRequest);
        $timeLineLog->year = json_encode($localRequest);
        $timeLineLog->local_watch_line = json_encode($serverRequest);
        $timeLineLog->server_watch_line = date('Y-m-d', $this->_setDate());
        $timeLineLog->date = $this->_setDate();
        $timeLineLog->save(false);

    }

    /**
     * 处理昨天产生的日志
     * @param $yesterdayLog
     */
    public function analyseUserBehavior($yesterdayLog)
    {
        $data = $this->getSSH()->exec("cat $yesterdayLog | grep Token | awk -F '|' '{print $9 }' | sort -u");
        $activeUser = array_filter(preg_split('/[;\r\n]+/s', $data));
        echo "需要处理" . count($activeUser) . PHP_EOL;
        if (!empty($activeUser)) {
            foreach ($activeUser as $user) {

                //对每个用户进行执行统计
                $log = $this->_initialData();
                $log['mac'] = $user;
                $log['login_time'] = $this->_grep($user, "awk -F '|' '{print $3}' | head -n 1");
                $log['last_time'] = $this->_grep($user, "awk -F '|' '{print $3}' | tail -n 1");
                $log['total_request'] = $this->_grep($user, "awk -F '|' '{print $3}'| wc -l");
                $log['token_request'] = $this->_grep($user, "grep 'getClientToken' | wc -l");
                $log['token_success'] = $this->_grep($user, "grep 'getClientToken' | grep 'error|success' | wc -l");
                $log['ott_request'] = $this->_grep($user, "grep 'getOttNewList'  | wc -l");
                $log['renew_request'] = $this->_grep($user, "grep 'renew'  | wc -l");
                $log['parade_request'] = $this->_grep($user, "grep 'getParadeList'  | wc -l");
                $log['time_request'] = $this->_grep($user, "grep 'getServerTime' | wc -l");
                $log['iptv_request'] = $this->_grep($user, "grep 'getIptvList'  | wc -l");
                $log['app_request'] = $this->_grep($user, "grep 'getApp|getNewApp' | wc -l");
                $log['auth_request'] = $this->_grep($user, "grep 'authMacDevice' | wc -l");
                $log['register_request'] = $this->_grep($user, "grep 'register' | wc -l");
                $log['market_request'] = $this->_grep($user, "grep 'getAppMarket' | wc -l");

                $log['firmware_request'] = $this->_grep($user, "grep 'getFirmware|getAndroidFirmware' | wc -l ");
                $log['ip_change'] = $this->_grep($user, "awk -F '|' '{print $5}' | sort -u | wc -l") - 1;

                if ($log['last_time'] < $log['login_time']) {
                    $lastTime = strtotime($log['last_time']) + 86400;
                } else {
                    $lastTime = strtotime($log['last_time']);
                }

                $during = ($lastTime - strtotime($log['login_time']));
                if ($during == 0) {
                    continue;
                }

                $log['request_rate'] = ceil($log['total_request'] / $during);
                $log['active_hour'] = sprintf("%2.2f", $during / 3600);
                $log['is_valid'] = !is_null(Mac::find()->where(['mac' => $log['mac']])->one()) ? 1 : 0;
                $log['exception'] = 0;
                //$log['request_tv'] = str_replace('\r','|', trim($this->getSSH()->exec("cat $yesterdayLog | grep $user | grep -vE 'getChannelIcon|getParade|default|getServerTime'| awk -F '|' '{print $11}' || sort -r -u")));

                $appLog = new AppLog();
                foreach ($log as $field => $value) {
                    $appLog->$field = $value;
                }
                $appLog->save(false);
                echo "正在处理{$user}...请求总次数{$log['total_request']}\n";
            }
        }

    }

    protected function getSSH()
    {
        return MySSH::singleton();
    }

    protected function getYesterdayLog()
    {
        return $this->path . '/'. date('Y', $this->_setDate()) . '/' . sprintf('%02d', date('m', $this->_setDate())) . '/' . date('Ymd', $this->_setDate()) . '.log';
    }

    protected function _grep($user, $condition)
    {
        $shell = "cat {$this->yesterdayLog} | grep '|{$user}|' | $condition";
        return trim($this->getSSH()->exec($shell));
    }

    /**
     *
     */
    protected function _initialData()
    {
        $strTime = date('Y-m-d');

        return [
            'month' => date('m'),
            'week' => ceil(((strtotime($strTime) - strtotime(date('Y') . "-01-01 00:00:00"))) / (7 * 86400)),
            'date' => date('Y-m-d', $this->_setDate())
        ];
    }

    private function _setDate()
    {
        return strtotime('yesterday');
    }

}