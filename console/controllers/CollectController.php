<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/11
 * Time: 18:17
 */

namespace console\controllers;

use console\collectors\youtube\Searcher;
use console\models\Karaoke;
use console\models\Movie;
use console\models\Tv;
use yii\console\Controller;

/**
 * 采集控制器
 * Class CollectController
 * @package console\controllers
 */
class CollectController extends Controller
{
    // karaoke
    public function actionKaraoke()
    {
        $search = new Searcher(new Karaoke());
        $options = [
            'q' => 'karaoke beat chuẩ',//
            'maxResults' => 50,
            'type' =>  'video',
            'order' => 'relevance',
            'videoDuration' => 'short',
        ];
        $search->setQueryOption($options);
        $search->start();
    }

    // 采集Youtube 电视剧
    public function actionTv($query)
    {
        $search = new Searcher(new Tv(), 'playlist');
        $options = [
            'q' => $query,//
            'maxResults' => 50,
            'type' =>  'video',
        ];
        $search->setQueryOption($options);
        $search->start();
    }

    // 采集Youtube电影
    public function actionMovie($query)
    {
        $search = new Searcher(new Movie());
        $options = [
            'q' => $query,//
            'maxResults' => 50,
            'type' =>  'video',
            'order' => 'relevance',
            'videoDuration' => 'short',
        ];
        $search->setQueryOption($options);
        $search->start();
    }


}