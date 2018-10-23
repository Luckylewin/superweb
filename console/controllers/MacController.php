<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/12
 * Time: 10:26
 */

namespace console\controllers;

use backend\models\Mac;
use console\jobs\SyncOnlineStateJob;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class MacController extends Controller
{

    public function actionSync()
    {
        SyncOnlineStateJob::start();
    }

    /**
     * 批量导入MAC地址
     */
    public function actionImport()
    {
        $start = '';
        $end = '';
        $contractTime = '1 year';

        $connection = Yii::$app->db;
        $success = $total = 0;

        $this->stdout('任务开始,请耐心等待' . PHP_EOL, Console::FG_GREEN);

        for($i = hexdec($start); $i <= hexdec($end); $i++) {
            $total++;
            $connection->transaction(function() use ($i, $contractTime, &$success){
                 $value = sprintf("%012x",$i);
                 $exist = Mac::find()->where(['MAC' => $value, 'SN'=> $value])->exists();

                 if ($exist == false) {
                     $mac = new Mac();
                     $mac->SN = $value;
                     $mac->MAC = $value;
                     $mac->regtime = date('Y-m-d H:i:s');
                     $mac->contract_time = $contractTime;
                     $mac->use_flag = 0;

                     try {
                         $mac->save(false);
                         $this->stdout("增加 {$value}-{$value}".PHP_EOL, Console::FG_BLUE);
                     } catch (\Exception $e) {}

                     $success++;
                     file_put_contents('/tmp/' . date('Ymd') . 'mac_import.log', $mac->MAC . ' ' . $mac->SN . PHP_EOL , FILE_APPEND);
                 }
            });

        }


        $this->stdout("本次导入{$total}条,成功导入{$success}" . PHP_EOL, Console::FG_GREEN);
    }

}