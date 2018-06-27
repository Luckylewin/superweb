<?php

namespace backend\controllers;

use backend\models\AppLog;
use backend\models\ProgramLog;
use backend\models\TimelineLog;
use yii\helpers\ArrayHelper;
use \Yii;
use yii\redis\Connection;

class LogController extends BaseController
{

    /**
     * @var Connection
     */
    public $redis;

    public function actionIndex()
    {
        $type = \Yii::$app->request->get('type');
        $value = \Yii::$app->request->get('value');

        $unit = 1;

        switch ($type)
        {
            case 'date':
                $unit = 1;
                $month = str_replace('/','-', $value);
                $where = ['like', 'date', ["$month"]];
                $timeLineLog = TimelineLog::find()->andWhere($where)->one();
                $program = ProgramLog::find()->andWhere($where)->one();
                break;
            case 'month':
                $unit = 30;
                $month = str_replace('/','-', $value);
                $where = ['like', 'date', ["$month"]];
                $timeLineLog = TimelineLog::find()->andWhere($where)->one();
                $program = ProgramLog::find()->andWhere($where)->one();
                break;
            default:
                $where = ['date' => date('Y-m-d',strtotime('yesterday'))];
                $timeLineLog = TimelineLog::findOne($where);
                $program = ProgramLog::findOne($where);
                break;
        }

        $programLog = ['all_program'=>[], 'local_program'=>[], 'server_program'=>[], 'all_program_sum'=>0];
        $log = ['default' => [0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0]];


        //接口时段调用统计
        if ($timeLineLog) {
            $log['total_line'] = json_decode($timeLineLog->total_line, true);
            $log['watch_line'] = json_decode($timeLineLog->watch_line, true);
            $log['token_line'] = json_decode($timeLineLog->token_line, true);
            $log['local_watch_line'] = json_decode($timeLineLog->local_watch_line, true);
            $log['server_watch_line'] = json_decode($timeLineLog->server_watch_line, true);
        }


        array_walk($log, function(&$v, $k) use ($unit) {
             if (is_array($v)) {
                 array_walk($v, function(&$_v, $k) use ($unit) {
                     $_v = ceil($_v / $unit);
                 });
             }
        });

        //节目观看统计

        if ($program) {
            $programLog['all_program'] = current(array_chunk(json_decode($program->all_program, true), 12,true));
            $programLog['local_program'] = current(array_chunk(json_decode($program->local_program, true), 12,true));
            $programLog['server_program'] = current(array_chunk(json_decode($program->server_program, true), 12,true));
            $programLog['all_program_sum'] = $program->all_program_sum;
        }

        $fields = ['log', 'programLog'];
        foreach ($fields as $field) {
            array_walk($$field, function(&$v, $k) use ($unit){
                if (is_numeric($v)) {
                    $v = ceil($v / $unit);
                }
            });
        }

        //昨日活跃用户
        return $this->render('index', [
            'log' => $log,
            'programLog' => $programLog
        ]);
    }

    /**
     *  实时统计接口调用情况
     */
    public function actionNow()
    {
        $this->redis = Yii::$app->redis;
        $this->redis->select(14);

        $data = [];

        //全部接口的调用情况
        $key = date('m-d:') . 'all';
        $data['all'] = $this->hgetallMap($this->redis->hgetall($key));

        $keys = $this->generateHour();
        foreach ($keys as $key => &$val) {
            if ($val['flag']) {
                $result = $this->redis->hgetall($val['key']);
                $val['val'] = $this->hgetallMap($result);
                if (!isset($data['all'][$key])) {
                    $data['all'][$key] = 0;
                }
            } else {
                $val['val'] = [];
                $data['all'][$key] = 0;
            }
        }

        ksort($data['all']);

        // 重要接口的调用情况
        $interfaces = ['getClientToken', 'getOttNewList', 'getCountryList'];
        foreach ($interfaces as $interface) {
            $interfaceData = [];
            foreach ($keys as $key => $val) {
                if (isset($val['val'][$interface])) {
                     $interfaceData[$key] = $val['val'][$interface];
                } else {
                     $interfaceData[$key] = 0;
                }
            }
            $data[$interface] = $interfaceData;
        }

        return $this->render('now', [
            'data' => $data,
            'programLog' => ['all_program' => []],

        ]);
    }

    private function hgetallMap($data)
    {
        $return = [];

        if ($data) {
            $len = count($data);
            for ($i = 0; $i < $len; $i+=2) {
                $return[$data[$i]] = $data[$i+1];
            }
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * 小时数组
     * @return array
     */
    public function generateHour()
    {
        $currentDay = date('m-d:');
        $currentHour = date('H');

        $keys = [];

        for ($h = 0; $h <= 23; $h++) {
            $h = sprintf('%02d', $h);
            $keys[] = [
                'key' => $currentDay . $h,
                'flag' => $h <= $currentHour ? true : false
            ];
        }

        return $keys;
    }


}
