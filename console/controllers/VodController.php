<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 13:22
 */

namespace console\controllers;

use Yii;
use console\collectors\local\VodCollector;
use console\models\Cartoon;
use console\models\Movie;
use console\models\Variety;
use yii\console\Controller;
use yii\helpers\Console;

class VodController extends Controller
{

    // 清除旧数据
    private function ActionClearData()
    {
        $this->stdout("清除旧数据", Console::FG_GREEN);
        Yii::$app->db->createCommand('truncate table iptv_vod')->query();
        Yii::$app->db->createCommand('truncate table iptv_play_group')->query();
        Yii::$app->db->createCommand('truncate table iptv_vodlink')->query();
        $this->stdout("数据清除完毕", Console::FG_GREEN);
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
        return $vodCollector = new VodCollector(new Movie(),[
            'dir'      => '/home/newpo/pinyin/hangju/',
            'playpath' => '/vod/hangju',
            'type'     => 'serial',
            'language' => '韩语',
            'area'     => '韩国'
        ]);
    }

    private function local()
    {
        return $vodCollector = new VodCollector(new Movie(),[
            'dir'      => '/home/newpo/pinyin/neidi/',
            'playpath' => '/vod/neidi',
            'type'     => 'serial',
            'language' => '中文',
            'area'     => '中国内地'
        ]);
    }

    private function hk()
    {
        return $vodCollector = new VodCollector(new Movie(),[
            'dir'      => '/home/newpo/pinyin/gangju/',
            'playpath' => '/vod/gangju',
            'type'     => 'serial',
            'language' => '中文',
            'area'     => '中国香港'
        ]);
    }

    private function us()
    {
        return $vodCollector = new VodCollector(new Movie(),[
            'dir'      => '/home/newpo/pinyin/meiju/',
            'playpath' => '/vod/meiju',
            'type'     => 'serial',
            'language' => '英语',
            'area'     => '美国'
        ]);
    }

}