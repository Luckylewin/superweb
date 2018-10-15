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
use yii\console\Controller;

class CollectController extends Controller
{
    public function actionKaraoke()
    {
        $search = new Searcher(new Karaoke());
        $search->setQueryOption(
            'karaoke beat chuẩ', Karaoke::LANG_VN, 'short'
        );
        $search->start();
    }

    public function actionMovie()
    {
        $search = new Searcher(new Movie());
        $search->setQueryOption(
            'vietnam movie 2018', Karaoke::LANG_VN, 'long'
        );
        $search->start();
    }

    public function actionTv()
    {

    }

}