<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 13:22
 */

namespace console\controllers;

use backend\models\PlayGroup;
use common\models\Vodlink;
use Yii;
use common\models\Vod;
use console\models\Tv;
use console\traits\Similar;
use console\collectors\local\VodCollector;
use console\models\Cartoon;
use console\models\Movie;
use console\models\Variety;
use yii\console\Controller;
use yii\helpers\Console;

class VodController extends Controller
{
    use Similar;

    // 清除旧数据
    public function actionClearData()
    {
        $this->stdout("清除旧数据" . PHP_EOL, Console::FG_GREEN);
        Yii::$app->db->createCommand('truncate table iptv_vod')->query();
        Yii::$app->db->createCommand('truncate table iptv_play_group')->query();
        Yii::$app->db->createCommand('truncate table iptv_vodlink')->query();
        $this->stdout("数据清除完毕" . PHP_EOL, Console::FG_GREEN);
    }

    public function actionDisk($type="movie")
    {
        switch ($type)
        {
            case 'movie':
                $vodCollector = $this->movie();
                break;
            case 'cartoon':
                $vodCollector = $this->cartoon();
                break;
            case 'variety':
                $vodCollector = $this->variety();
                break;
            case 'kr':
                $vodCollector = $this->kr();
                break;
            case 'hk':
                $vodCollector = $this->hk();
                break;
            case 'local':
                $vodCollector = $this->local();
                break;
            case 'us':
                $vodCollector = $this->us();
                break;
            default;
                Console::error("不支持{$type}");
                return false;
        }

        return $vodCollector->doCollect();
    }

    public function actionDiskAll()
    {
        foreach (['movie','cartoon','variety','kr','hk','local','us'] as $item){
            $this->actionDisk($item);
        }
    }

    private function movie()
    {
        return $vodCollector = new VodCollector(new Movie(),[
            'dir'      => '/home/newpo/pinyin/movie/',
            'playpath' => '/vod/movie',
            'type'     => 'movie',
            'language' => '中文',
            'area'     => '中国'
        ]);
    }

    private function cartoon()
    {
        return $vodCollector = new VodCollector(new Cartoon(),[
            'dir'      => '/home/newpo/pinyin/dongman/',
            'playpath' => '/vod/dongman',
            'type'     => 'cartoon',
            'language' => '中文',
            'area'     => '中国'
        ]);
    }

    private function variety()
    {
        return $vodCollector = new VodCollector(new Variety(),[
            'dir'      => '/home/newpo/pinyin/zongyi/',
            'playpath' => '/vod/zongyi',
            'type'     => 'variety',
            'language' => '中文',
            'area'     => '中国'
        ]);
    }

    private function kr()
    {
        return $vodCollector = new VodCollector(new Tv(),[
            'dir'      => '/home/newpo/pinyin/hanju/',
            'playpath' => '/vod/hanju',
            'type'     => 'serial',
            'language' => '韩语',
            'area'     => '韩国',
            'genre'    => '韩剧'
        ]);
    }

    private function local()
    {
        return $vodCollector = new VodCollector(new Tv(),[
            'dir'      => '/home/newpo/pinyin/neidi/',
            'playpath' => '/vod/neidi',
            'type'     => 'serial',
            'language' => '中文',
            'area'     => '中国内地',
            'genre'    => '内地'
        ]);
    }

    private function hk()
    {
        return $vodCollector = new VodCollector(new Tv(),[
            'dir'      => '/home/newpo/pinyin/gangju/',
            'playpath' => '/vod/gangju',
            'type'     => 'serial',
            'language' => '中文',
            'area'     => '中国香港',
            'genre'    => '港剧'
        ]);
    }

    private function us()
    {
        return $vodCollector = new VodCollector(new Tv(),[
            'dir'      => '/home/newpo/pinyin/meiju/',
            'playpath' => '/vod/meiju',
            'type'     => 'serial',
            'language' => '英语',
            'area'     => '美国',
            'genre'    => '美剧'
        ]);
    }

    /**
     * 专题归类
     */
    public function actionLike()
    {
       $vods = Vod::find()->all();
       foreach ($vods as $vod) {
           $this->stdout("检测{$vod->vod_name}" . PHP_EOL);
           foreach ($vods as $_vod) {
               if ($vod->vod_id != $_vod->vod_id) {
                   $similarValue = ceil($this->getSimilar($vod->vod_name, $_vod->vod_name) * 10);
                   if ($similarValue > 9) {
                        $vod->vod_series = $this->getLCS($vod->vod_name, $_vod->vod_name);
                        $vod->vod_series = trim(preg_replace('/\s*S\d+\s*/', '', $vod->vod_series));
                        $vod->vod_series = preg_replace('/\s(?=\s)/', "\\1", $vod->vod_series);

                        $vod->save(false);
                        $this->stdout("{$vod->vod_name} 设置系列为 :{$vod->vod_series}" . PHP_EOL ,Console::FG_BLUE);
                   }
               }

           }
       }
    }


    public function actionTruncate()
    {
        Yii::$app->db->createCommand('truncate ' . Vod::tableName());
        Yii::$app->db->createCommand('truncate ' . Vodlink::tableName());
        Yii::$app->db->createCommand('truncate ' . PlayGroup::tableName());
    }

}