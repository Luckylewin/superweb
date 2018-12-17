<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;


use backend\models\MiddleParade;
use backend\models\Parade;
use backend\models\VodProfile;
use common\models\MainClass;
use common\models\OttChannel;
use common\models\OttLink;
use common\models\SubClass;
use common\models\Vod;
use console\collectors\profile\BAIDU;
use console\collectors\profile\DOUBAN;
use console\collectors\profile\MOVIEDB;
use console\collectors\profile\ProfilesSearcher;
use console\collectors\profile\SOKU;
use console\jobs\TranslateJob;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    public $message;
    public $day;


    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        return ExitCode::OK;
    }

    public function actionCache()
    {
        $data = MainClass::find()
                ->with('subChannel')
                ->asArray()
                ->all();

        foreach ($data as $class) {
            foreach ($class['subChannel'] as $channel) {

                 if (empty($channel['alias_name'])) continue;


                 $parade = Parade::find()->where(['channel_name' => $channel['alias_name']])
                                             ->andWhere(['<=', 'parade_date', date('Y-m-d', strtotime('+4 day'))])
                                             ->asArray()
                                             ->all();



                 if (!empty($parade)) {
                     $exist = MiddleParade::find()->where(['channel' => trim($channel['name']), 'genre' => trim($class['name'])])->exists();
                     if ($exist == false) {
                         $middleParade = new MiddleParade();
                         $middleParade->channel = $channel['name'];
                         $middleParade->genre = $class['name'];
                         $middleParade->parade_content = json_encode($parade);
                         $middleParade->save(false);
                     }
                 }
            }
        }

        echo 'ok';

    }

    public function actionMaintain()
    {
        $subclass = SubClass::find()->all();
        foreach ($subclass as $class) {
            if (is_null($class->mainClass)) {
                $class->delete();
                $this->stdout("删除" . $class->name . PHP_EOL);
            }
        }

        $channels = OttChannel::find()->all();
        foreach ($channels as $channel) {
            if (is_null($channel->subClass)) {
                $channel->delete();
                $this->stdout('删除' . $channel->name . PHP_EOL);
            }
        }

        $links = OttLink::find()->all();
        foreach ($links as $link) {
            if (is_null($link->channel)) {
                $link->delete();
                $this->stdout('删除' . $link->link . PHP_EOL);
            }
        }
    }

    public function actionAnna()
    {
        $channels = OttChannel::find()->all();
        foreach ($channels as $channel) {
            $links = $channel->ownLink;
            if (!empty($links) && count($links) > 1) {
                foreach ($links as $link) {
                    if (preg_match('/\.ts/' , $link->link)) {
                        $link->delete();
                    }
                }
            }
        }
    }

    public function actionTest()
    {
        ExitCode::OK;
    }

    public function actionLang()
    {
        TranslateJob::iptvType();
        TranslateJob::typeItem();
    }

    public function actionProfile()
    {
        $vods = Vod::find()->all();
        foreach ($vods as $vod) {
            $vod->vod_letter = $vod->getFirstLetter($vod->vod_name);
            $vod->vod_keywords = $vod->getKeyword($vod->vod_name);
            $vod->save(false);
        }

        /*$name = "大秦帝国 电视剧";
        $profile = BAIDU::searchByName($name, ['language' => 'zh-CN']);*/
    }


}
