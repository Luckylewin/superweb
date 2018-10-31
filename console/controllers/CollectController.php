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
    public function actionKaraoke($query = 'karaoke beat chuẩ', $area = 'Vietnam', $language = 'Vietnamese')
    {
        $karaoke = new Karaoke();
        $karaoke->setArea($area);
        $karaoke->setLanguage($language);

        $search  = new Searcher($karaoke);
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

    // 采集Youtube 电视剧
    public function actionTv($query = 'Phim truyền hình', $area = 'Vietnam', $language = 'Vietnamese')
    {
        $tv = new Tv();
        $tv->setArea($area);
        $tv->setLanguage($language);

        $search = new Searcher($tv, 'playlist');
        $options = [
            'q' => $query,//
            'maxResults' => 50,
            'type' =>  'playlist',
        ];
        $search->setQueryOption($options);
        $search->start();
    }

    // 采集Youtube电影
    public function actionMovie($query = "Vietnam movie", $area = 'Vietnam', $language = 'Vietnamese')
    {
        $movie  = new Movie();
        $movie->setArea($area);
        $movie->setLanguage($language);
        $search = new Searcher($movie);
        $options = [
            'q' => $query,//
            'maxResults' => 50,
            'type' =>  'video',
            'order' => 'relevance',
            'videoDuration' => 'long',
        ];
        $search->setQueryOption($options);
        $search->start();
    }


}