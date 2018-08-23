<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/22
 * Time: 18:31
 */

namespace console\queues;


use common\models\MainClass;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use backend\models\Parade;
use backend\models\MiddleParade;

/**
 * 生成中间表缓存
 * Class CacheParadeJob
 * @package console\queues
 */
class CacheParadeJob extends BaseObject implements JobInterface
{

    public function execute($queue)
    {
        try {
            // 清除中间表数据
            \Yii::$app->db->createCommand("DELETE FROM " . MiddleParade::tableName())->execute();
            \Yii::$app->db->createCommand("OPTIMIZE " . MiddleParade::tableName())->execute();
        } catch (\Exception $e) {

        }


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

    }
}