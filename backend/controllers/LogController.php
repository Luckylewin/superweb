<?php

namespace backend\controllers;

use backend\models\form\LogChangedForm;
use Yii;
use yii\redis\Connection;
use backend\models\LogInterface;
use backend\models\LogOttGenre;
use backend\models\LogStatics;
use backend\models\ProgramLog;

class LogController extends BaseController
{
    /**
     * @var Connection
     */
    public $redis;

    public function actionIndex()
    {
        $type = Yii::$app->request->get('type', 'date');
        $date = Yii::$app->request->get('value', date('Y-m-d', strtotime('yesterday')));
        $date = str_replace('/', '-', $date);

        // 按日期查询
        if ($type == 'date') {
            $model      = LogInterface::findByDate($date);
            $programLog = ProgramLog::findByDate($date);
            $statics    = LogStatics::findByDate($date);
            $genres     = LogOttGenre::findByDate($date);

        } else {
            // 按 月份进行查询
            list($year, $month) = explode('-', $date);
            $model      = LogInterface::findByMonth($year, $month);
            $programLog = ProgramLog::findByMonth($year, $month);
            $statics    = LogStatics::findByMonth($year, $month);
            $genres     = LogOttGenre::findByMonth($year, $month);
            print_r($genres);
        }

        if (empty($model)) {
            $this->setFlash('warning', Yii::t('backend', "No yesterday's log"));
        }

        return $this->render('index', [
            'programLog' => $programLog,
            'statics'    => $statics,
            'genres'     => $genres,
            'model'      => $model,
            'date'       => $date
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
            $program_rank = array_slice($program_rank, 0, 10);
            arsort($program_rank);
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

    public function actionChange($type = 'date', $attribute)
    {
        $model = new LogChangedForm();
        $model->year  = date('Y');
        $model->month = date('m');

        if ($this->getRequest()->isPost) {
            $model->load($this->getRequest()->post());
        }

        if ($type == 'date') {
           $data = LogStatics::getAttributeChangeDataWithMonth($attribute, $model->year,$model->month);
        }

        return $this->render('change', [
            'model' => $model,
            'data'  => $data,
            'title' => (new LogStatics())->attributeLabels()[$attribute],
            'time'  => $model->year . '年' . $model->month . '月'
        ]);
    }

    /**
     * hgetall 关联数组
     * @param $data
     * @return array|bool
     */
    protected function hgetallMap($data)
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
    protected function generateHour()
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
    protected function getCurrentX()
    {
        $currentHour = date('H');
        $x = [];
        for ($h = 0; $h <= $currentHour; $h++) {
            $x[] = sprintf('%02d', $h) . 'h';
        }

        return $x;
    }


}
