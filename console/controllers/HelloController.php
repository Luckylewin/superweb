<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;

use backend\models\LinkToScheme;
use backend\models\Scheme;
use common\models\OttLink;
use common\models\Vod;
use common\models\Vodlink;
use common\models\VodList;
use console\components\MySnnopy;
use console\models\movie\IMDB;
use console\models\movie\OMDB;
use console\models\profile;
use Symfony\Component\DomCrawler\Crawler;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    public $message;
    public $day;


    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        // $data = IMDB::search('Condorito La pelicula');
        // $data = OMDB::findByTitle('Adam Patel Real Magic');
        // $data = OMDB::findByIMDbID('tt4794544');
        // $data = profile::like('Cobras');

        /*
            $data = profile::search('Orgullo y Prejuicio y Zombis');
          var_dump($data);
        */

        $a = preg_match('/(S\d+)\s+(E\d+)/', 'Segundo Sol S01 E04', $match);
        print_r($match);
        echo $a = (int) $a[2];
        return ExitCode::OK;
    }


    public function actionScheme()
    {

    }


    public function actionTest()
    {
        $snnopy = MySnnopy::init();
        $snnopy->fetch('https://movie.douban.com/subject/26942674/');

        $crawler = new Crawler();
        $crawler->addHtmlContent($snnopy->results, "UTF-8");

        $crawler
           // ->filter('div#subject')
            ->filterXpath('//div[@id="info"]')
            ->filterXpath('//span[contains(./text(), "语言:")]/following::text()[1]')
            ->each(function(Crawler $node) {
                echo $node->text();
            })
        ;

    }
}
