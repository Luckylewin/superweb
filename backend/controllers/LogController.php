<?php

namespace backend\controllers;

use backend\models\AppLog;
use backend\models\ProgramLog;
use backend\models\TimelineLog;
use yii\helpers\ArrayHelper;

class LogController extends BaseController
{
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

        //具体数值统计
        $sumFields = 'count(id) as total,
                      sum(total_request) as total_request,
                      sum(token_request) as token_request,
                      sum(token_success) as token_success,
                      sum(iptv_request) as iptv_request,
                      sum(ott_request) as ott_request,
                      sum(app_request) as app_request,
                      sum(firmware_request) as firmware_request,
                      sum(active_hour) as active_hour,
                      sum(parade_request) as parade_request,
                      sum(renew_request) as renew_request,
                      sum(auth_request) as auth_request,
                      sum(time_request) as time_request,
                      sum(register_request) as register_request,
                      sum(market_request) as market_request,
                      sum(karaokelist_request) as karaokeList_request,
                      sum(karaoke_request) as karaoke_request';

        $validSumFields = 'count(id) as valid_total,
                           sum(total_request) as valid_total_request';

        $validAppLog = AppLog::find()->select($validSumFields)->where(['is_valid' => 1])->andWhere($where)->asArray()->one();
        $allAppLog   = AppLog::find()->select($sumFields)->andWhere($where)->asArray()->one();

        if ($allAppLog) {
            $appLog = ArrayHelper::merge($validAppLog, $allAppLog);
        }

        $fields = ['log', 'programLog', 'appLog'];
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
            'programLog' => $programLog,
            'appLog' => $appLog,
        ]);
    }

}
