<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;

use Yii;
use backend\models\MiddleParade;
use backend\models\Parade;
use backend\models\VodProfiles;
use common\models\MainClass;
use common\models\OttChannel;
use common\models\OttLink;
use common\models\SubClass;
use common\models\Vod;
use console\collectors\profile\MOVIEDB;
use console\jobs\TranslateJob;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Query;
use yii\helpers\Console;

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

    private function getWasteTime($time)
    {
        if ($time < 60) {
            return $time . ' s';
        } else if ($time > 60 && $time < 3600) {
            return ceil($time/60) . ' min ' . ($time  % 60 ) . ' s';
        } else {
            $hour = ceil($time/3600);
            $lastSecond =$time % 3600;
            return  $hour. ' h ' . ceil($lastSecond/60) . ' min ' . ($lastSecond % 60) . 's';
        }
    }

    private function getNoDataID()
    {
        $tmdb = Yii::$app->cache->get('tmdb');
        if ($tmdb == false) {
            Yii::$app->cache->set('tmdb', json_encode([]));
            return [];
        }

        return $tmdb = json_decode($tmdb, true);
    }

    private function isSearch($id)
    {
        $tmdb = $this->getNoDataID();

        if (in_array($id, $tmdb)) {
            return false;
        }

        return true;
    }

    public function sleepPrint($min,$max)
    {
        $time = mt_rand($min, $max);
        while ($time > 0) {
            $this->stdout('.', Console::FG_GREY);
            $time --;
            sleep(1);
        }
    }

    public function actionFill()
    {
        $start = time();
        foreach (VodProfiles::find()->where(['tmdb_search' => 0])->batch(10) as $profiles) {
            foreach ($profiles as $profile) {

               $this->stdout(date('Y-m-d H:i:s  ') . "TMDB采集: {$profile->name}", Console::FG_CYAN);

               try {

                   if (empty($profile->name)) continue;

                   if ($this->isSearch($profile->id) == false) {
                       /* @var $profile VodProfiles */
                       VodProfiles::updateAll(['tmdb_search' => 1],['name' => $profile->name]);
                       $this->stdout("X ",Console::FG_RED);
                       $this->stdout("没有数据，跳过".PHP_EOL, Console::FG_GREY);
                       continue;
                   }

                   // MOVIEDB::$actor_switch = false;

                   $data = MOVIEDB::searchByName($profile->name, [
                       'language' => 'zh-CN'
                   ]);

                   /* @var $profile VodProfiles */

                   $time = $this->getWasteTime(time() - $start);

                   if ($data) {
                       $profile->tmdb_id = $data['tmdb_id']??'';
                       $profile->tmdb_score = $data['vod_gold']??'';
                       $profile->alias_name = $data['vod_ename']??'';
                       $profile->cover = $data['vod_pic']??'';
                       $profile->image = $data['vod_pic_bg']??'';
                       $profile->plot = $data['vod_content']??'';
                       $profile->language = $data['vod_language']??'';
                       $profile->year = $data['vod_year']??'';
                       $profile->date = $data['vod_filmtime']??'';
                       $profile->tag = $data['vod_type']??'';
                       $profile->imdb_id = $data['vod_imdb_id']??'';
                       $profile->profile_language = 'zh-CN';
                       $profile->tmdb_search = 1;
                       $profile->save(false);
                       $this->stdout("√ ",Console::FG_GREEN);

                   } else {
                       VodProfiles::updateAll(['tmdb_search' => 1],['name' => $profile->name]);
                       $tmdb = $this->getNoDataID();
                       $tmdb[] = $profile->id;
                       Yii::$app->cache->set('tmdb', json_encode($tmdb));
                       $this->stdout("X ",Console::FG_RED);
                   }

                   $this->sleepPrint(2,3);
                   $this->stdout("任务已运行{$time}" . PHP_EOL,Console::FG_BLUE);

               } catch (\Exception $e) {
                   $this->stdout("Error:" . $e->getMessage() . PHP_EOL,Console::FG_RED);
                   continue;
               }

            }
        }
    }

}
