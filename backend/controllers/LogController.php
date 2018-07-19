<?php

namespace backend\controllers;

use backend\models\AppLog;
use backend\models\LogInterface;
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

        // 查询
        $model = LogInterface::findByDate($value);
        $programLog = ProgramLog::findByDate($value);

        if (empty($model)) {
            $this->setFlash('warning', '没有查询到日志 :(');
        }

        return $this->render('index', [
            'programLog' => $programLog,
            'model' => $model
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
        $key = "interface:" . date('m-d:') . 'hour';
        $data['all'] = $this->hgetallMap($this->redis->hgetall($key));


        //节目播放接口的调用情况
        $key = 'program:' . date('m-d:') . 'hour';
        $data['program'] = $this->hgetallMap($this->redis->hgetall($key));

        $keys = $this->generateHour();

        foreach ($keys as $key => &$val) {
            if ($val['flag']) {

                $result = $this->redis->hgetall("interface:" . $val['key']);
                $val['val'] = $this->hgetallMap($result);
                if (!isset($data['all'][$key])) {
                    $data['all'][$key] = 0;
                }
                if (!isset($data['program'][$key])) {
                    $data['program'][$key] = 0;
                }
            } else {
                $val['val'] = [];
                $data['all'][$key] = 0;
                $data['program'][$key] = 0;
            }
        }

        ksort($data['all']);
        ksort($data['program']);

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


        //节目收看排行
        $key = "program:" . date('m-d:') . 'set';
        $program_rank = $this->hgetallMap($this->redis->hgetall($key));
        if ($program_rank) {
            $program['key'] = array_keys($program_rank);
            $program['value'] = array_values($program_rank);
        } else {
            $program['key'] = $program['value'] = [];
        }

        return $this->render('now', [
            'data' => $data,
            'axisX' => $this->getCurrentX(),
            'programLog' => ['all_program' => []],
            'program' => $program

        ]);
    }

    /**
     * hgetall 关联数组
     * @param $data
     * @return array|bool
     */
    private function hgetallMap($data)
    {
        $return = [];

        if ($data) {
            $len = count($data);
            for ($i = 0; $i < $len; $i+=2) {
                if (is_numeric($data[$i])) {
                    $index = (integer)($data[$i]);
                    $return[$index] = $data[$i+1];
                } else {
                    $return[$data[$i]] = $data[$i+1];
                }
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

    /**
     * 实时x轴
     * @return array
     */
    public function getCurrentX()
    {
        $currentHour = date('H');
        $x = [];
        for ($h = 0; $h <= $currentHour; $h++) {
            $x[] = sprintf('%02d', $h) . 'h';
        }

        return $x;
    }

}
