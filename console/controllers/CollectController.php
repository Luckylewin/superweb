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
use yii\console\Controller;

class CollectController extends Controller
{
    public function actionKaraoke()
    {
        $search = new Searcher(new Karaoke());
        $search->setQueryOption(
            'Vietnamese Movie', Karaoke::LANG_VN
        );
        $search->start();
    }

    public function actionMovie()
    {

    }

    public function actionTv()
    {

    }

}