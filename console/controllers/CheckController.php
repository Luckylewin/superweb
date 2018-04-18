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

        //获取ffprobe路径
        $ffprobe = $ssh->exec('whereis ffprobe');
        if (empty($ffprobe)) {
            echo "----未安装ffprobe----", PHP_EOL;
            return ExitCode::SOFTWARE;
        } else {
            preg_match('/(\/\w+)+\w+/', $ffprobe, $ffprobePath);

        }

        foreach ($query->batch() as $karaokes) {
            foreach ($karaokes as $karaoke) {
                echo "----正在检测----- {$karaoke['url']}",PHP_EOL;
                $urlID = $karaoke['url'];
                $url = common::getVideoUrl($urlID);
                if ($url == false) {
                    $karaoke = Karaoke::findOne($karaoke['ID']);
                    $karaoke->is_del = 1;
                    $karaoke->save();
                    echo "----无法获取真实地址----", PHP_EOL;continue;
                }

                if ($url) {
                    $execRes = $ssh->exec("$ffprobePath  -print_format json -show_error '$url'");
                    $analRes = common::getStatus($karaoke['albumName'], $karaoke['url'], $execRes);

                    if ($analRes['status'] == false) {
                        echo "----结果：播放失败({$analRes['msg']})----- ";continue;
                    }

                    if (!$karaoke['is_del']) {
                        $karaoke = Karaoke::findOne($karaoke['ID']);
                        $karaoke->is_del = 0;
                        $karaoke->save();
                    }

                    echo "----结果：播放成功----- ", PHP_EOL;

                    sleep(mt_rand(20,30));
                }

            }
        }

        $ssh->close();
        return ExitCode::OK;
    }

    public function actionAnnVod()
    {

    }

}