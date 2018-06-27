<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/10
 * Time: 18:20
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use backend\models\Crontab;

/**
 * 定时任务调度控制器
 * @author jlb
 */
class CrontabController extends Controller
{

    /**
     * 定时任务入口
     * @return int Exit code
     */
    public function actionIndex()
    {

        $crontab = Crontab::findAll(['switch' => 1]);

        $tasks = [];

        foreach ($crontab as $task) {

            // 第一次运行,先计算下次运行时间
            if (!$task->next_rundate) {
                $this->updateNextTime($task);
                continue;
            }

            // 判断运行时间到了没
            if ($task->next_rundate <= date('Y-m-d H:i:s')) {
                $tasks[] = $task;
            }
        }

        $this->executeTask($tasks);

        return ExitCode::OK;
    }

    private function updateNextTime(Crontab $task)
    {
        $task->next_rundate = $task->getNextRunDate();
        $task->save(false);
    }

    /**
     * @param  array $tasks 任务列表
     * @author jlb
     */
    public function executeTask(array $tasks)
    {

        $pool = [];
        $startExectime = $this->getCurrentTime();

        foreach ($tasks as $task) {

            $pool[] = proc_open("php yii $task->route", [], $pipe);
        }

        // 回收子进程
        while (count($pool)) {
            foreach ($pool as $i => $result) {
                $processInfo = proc_get_status($result);
                if($processInfo['running'] == FALSE) {
                    proc_close($result);
                    unset($pool[$i]);
                    # 记录任务状态
                    $tasks[$i]->exectime     = round($this->getCurrentTime() - $startExectime, 2);
                    $tasks[$i]->last_rundate = date('Y-m-d H:i');
                    $tasks[$i]->next_rundate = $tasks[$i]->getNextRunDate();
                    $tasks[$i]->status       = 0;
                    // 任务出错
                    if ($processInfo['exitcode'] !== ExitCode::OK) {
                        $tasks[$i]->status = 1;
                    }

                    $tasks[$i]->save(false);
                }
            }
        }
    }

    private function getCurrentTime ()
    {
        list ($microSec, $sec) = explode(" ", microtime());
        return (float)$microSec + (float)$sec;
    }

}