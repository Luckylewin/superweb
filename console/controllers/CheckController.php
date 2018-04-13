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
            return false;
        } else {
            preg_match('/(\/\w+)+\w+/', $ffprobe, $ffprobePath);

        }

        foreach ($query->batch() as $karaokes) {
            foreach ($karaokes as $karaoke) {
                echo "----正在检测----- {$karaoke['url']}",PHP_EOL;
                $urlID = $karaoke['url'];
                $url = common::getVideoUrl($urlID);
                if ($url) {
                    $shell = "$ffprobePath  -print_format json -show_error '$url'";
                    $execRes = $ssh->exec($shell);
                    $analRes = common::getStatus($karaoke['albumName'], $karaoke['url'], $execRes);
                    if ($analRes['status'] == false) {
                        $karaoke = Karaoke::findOne($karaoke['ID']);
                        $karaoke->is_del = 1;
                        $karaoke->save();
                        echo "----结果：播放失败({$analRes['msg']})----- ",PHP_EOL;
                    } else {
                        if (!$karaoke['is_del']) {
                            $karaoke = Karaoke::findOne($karaoke['ID']);
                            $karaoke->is_del = 0;
                            $karaoke->save();
                        }
                        echo "----结果：播放成功----- ",PHP_EOL;
                    }
                    sleep(mt_rand(20,30));
                }else {
                    echo "----无法获取真实地址----", PHP_EOL;
                }

            }
        }

        $ssh->close();
    }

}