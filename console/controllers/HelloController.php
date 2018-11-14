<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;

use backend\models\Cache;
use backend\models\IptvType;
use backend\models\IptvTypeItem;
use backend\models\Mac;
use backend\models\MiddleParade;
use backend\models\Parade;
use backend\models\PlayGroup;
use common\models\MainClass;
use common\models\OttChannel;
use common\models\OttLink;
use common\models\SubClass;
use common\models\Type;
use common\models\Vod;
use common\models\Vodlink;
use common\models\VodList;
use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Query;
use Yii;
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
        $query = new Query();
        $query->from('mac_detail');
        foreach($query->batch() as $users){
            foreach($users as $user){
               if (!empty($user['client_id']) && !empty($user['MAC'])) {
                   Mac::updateAllCounters(['client_id' => $user['client_id']], [
                      'MAC' => $user['MAC']
                   ]);
               }
            }
        }


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

    public function actionTest()
    {
        $vodList = vodList::find()->all();
        foreach ($vodList as $list) {
            // 判断是否存在 iptv types
           $typeList = IptvType::find()->where(['vod_list_dir' => $list->list_dir, 'field' => 'type'])->one();
           if (empty($typeList)) {
               $fields = ['year', 'type', 'area', 'language'];
               foreach ($fields as $field) {
                   $iptvType = new IptvType();
                   $iptvType->field = $field;
                   $iptvType->name  = $field;
                   $iptvType->vod_list_dir = $list->list_dir;
                   $iptvType->vod_list_id  = $list->list_id;
                   $iptvType->save(false);
               }
           } else {
               $vods = Vod::find()->where(['vod_cid' => $list->vod_id])->all();
               foreach ($vods as $vod) {
                   $typesString = explode(',', $vod->vod_type);
                   if (!empty($typesString)) {
                        foreach ($typesString as $type) {
                            // 判断是否存在于
                        }
                   }
               }
           }
        }
    }


}
