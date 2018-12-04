<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/24
 * Time: 17:19
 */

namespace console\jobs;

use backend\models\Parade;
use backend\models\MiddleParade;
use common\models\MainClass;

/**
 * 预告常规维护工作类
 * Class ParadeJob
 * @package console\jobs
 */
class ParadeJob
{
    public static function generateCache()
    {
        try {
            // 清除中间表数据
            \Yii::$app->db->createCommand("DELETE FROM " . MiddleParade::tableName())->execute();
            \Yii::$app->db->createCommand("OPTIMIZE " . MiddleParade::tableName())->execute();
        } catch (\Exception $e) {

        }

        // 删除过期的预告数据
        $date = date('Y-m-d',strtotime('-2 day'));
        Parade::deleteAll("parade_date <= '$date'");

        $data = MainClass::find()
            ->with('subChannel')
            ->asArray()
            ->all();

        foreach ($data as $class) {
            static::generateChannelCache($class['subChannel'], $class['name']);
            if ($class['name'] != $class['list_name']) {
               static::generateChannelCache($class['subChannel'], $class['list_name']);
            }
        }
    }

    private static function generateChannelCache($channels, $genre)
    {
        if (empty($channels)) {
            return false;
        }

        foreach ($channels as $channel) {
            if (empty($channel['alias_name'])) continue;
            $parade = Parade::find()->where(['channel_name' => $channel['alias_name']])
                ->andWhere(['<=', 'parade_date', date('Y-m-d', strtotime('+7 day'))])
                ->asArray()
                ->all();

            if (!empty($parade)) {
                $exist = MiddleParade::find()->where(['channel' => trim($channel['name']), 'genre' => trim($genre)])->exists();
                if ($exist == false) {
                    $middleParade = new MiddleParade();
                    $middleParade->channel = $channel['name'];
                    $middleParade->genre = $genre;
                    $middleParade->parade_content = json_encode($parade);
                    $middleParade->save(false);
                }
            }
        }

    }


}