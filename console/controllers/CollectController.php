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
    // 采集kalaoke
    public function actionKaraoke()
    {
        $search = new Searcher(new Karaoke());
        $search->setQueryOption(
            'karaoke beat chuẩ', Karaoke::LANG_VN, 'short'
        );
        $search->start();
    }

    // 采集Youtube 电视剧
    public function actionTv($query)
    {
        $search = new Searcher(new Tv());
        $search->setQueryOption(
            $query, Karaoke::LANG_ZH, 'long'
        );
        $search->start();
    }

    // 采集Youtube电影
    public function actionMovie()
    {
        $search = new Searcher(new Movie());
        $search->setQueryOption(
            'vietnam movie 2018', Karaoke::LANG_VN, 'long'
        );
        $search->start();
    }


}