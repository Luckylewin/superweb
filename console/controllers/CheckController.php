<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/12
 * Time: 10:33
 */

namespace console\controllers;

use backend\components\MySSH;
use backend\models\Karaoke;
use console\models\common;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Query;

class CheckController extends Controller
{
    /**
     * 任务检测
     */
    public function actionKaraoke()
    {
        $ssh = MySSH::singleton();
        $query = new Query();
        $query->from(Karaoke::tableName());
        foreach ($query->batch() as $karaokes) {
            foreach ($karaokes as $karaoke) {
                echo "----正在检测----- {$karaoke['url']}",PHP_EOL;
                $urlID = $karaoke['url'];
                $url = common::getVideoUrl($urlID);
                $execRes = $ssh->exec("ffprobe -show_error -print_format json $url");
                $analRes = common::getStatus($karaoke['albumName'], $karaoke['url'], $execRes);
                if ($analRes['status'] == false) {
                    $karaoke = Karaoke::findOne($karaoke['ID']);
                    $karaoke->is_del = 1;
                    $karaoke->save();
                    echo "----结果：播放失败----- ",PHP_EOL;
                } else {
                    echo "----结果：播放成功----- ",PHP_EOL;
                }
                sleep(mt_rand(20,30));
            }
        }

        $ssh->close();
    }

}