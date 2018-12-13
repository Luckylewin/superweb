<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 13:22
 */

namespace console\controllers;

use console\collectors\local\VodCollector;
use console\models\Cartoon;
use console\models\Movie;
use console\models\Variety;
use yii\console\Controller;
use yii\helpers\Console;

class VodController extends Controller
{
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

    public function actionSerial()
    {
        foreach (['cartoon','variety','kr','hk','local','us'] as $item){
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
            'type'     => 'movie',
            'language' => '中文',
            'area'     => '中国'
        ]);
    }

    private function variety()
    {
        return $vodCollector = new VodCollector(new Variety(),[
            'dir'      => '/home/newpo/pinyin/zongyi/',
            'playpath' => '/vod/zongyi',
            'type'     => 'movie',
            'language' => '中文',
            'area'     => '中国'
        ]);
    }

    private function kr()
    {
        return $vodCollector = new VodCollector(new Movie(),[
            'dir'      => '/home/newpo/pinyin/hangju/',
            'playpath' => '/vod/hangju',
            'type'     => 'movie',
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