<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/31
 * Time: 15:05
 */

namespace console\models\parade;

use Yii;
use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use yii\helpers\ArrayHelper;

class beginsport extends CommonParade implements collector
{
    //public $url = 'http://www.beinsports.com/france/programmes';2018-06-01
    public $url = 'https://epg.beinsports.com/utctime_france.php?cdate={DATE}&offset=+8&mins=00&category=sports&id=123';
    public $url2 = 'https://epg.beinsports.com/utctime_us.php?cdate={DATE}&offset=+8&mins=00&category=sports&id=123';
    public $debug = false;

    public function start()
    {
        $tasks = $this->getUrlGroup();

        foreach ($tasks as $task) {
            $this->getOneDay($task['date'], $task['url']);
            $this->_sleep(2, 5);
        }
    }

    /**
     * @param $date
     * @param $url
     */
    public function getOneDay($date, $url)
    {
        $snnopy = MySnnopy::init();
        $snnopy->fetch($url);
        $data = $snnopy->results;

        $dom = new Crawler();
        $dom->addHtmlContent($data);

        $dom->filterXPath("//div[@class='row no-gutter']")->each(function(Crawler $node) use($date, $url) {
            $id = $node->attr('id');

            if (preg_match('/^channels_\d+/', $id)) {
                //获取频道名称
                $picture = $node->filter('.channel .centered img')->attr('src');
                $channelName = 'Bein-Sport-' . basename($picture, '.svg');
                $paradeData = [];

                //获取预告
                $node->filter('.slider')->each(function (Crawler $divDom) use(&$paradeData, $date, $channelName, $url) {
                     $className = $divDom->attr('id');
                     $divDom->filter('li')->each(function(Crawler $liDom) use(&$paradeData, $date, $className) {
                         $parent = $liDom->attr('parent');
                         try{
                             if ($liDom->filter('.onecontent .title')->count() && $parent == $className) {
                                 $paradeTime = $liDom->filter('.timer p')->eq(0)->text();
                                 $paradeTime = explode('-', $paradeTime);
                                 $paradeTime = substr($paradeTime[0],0,5 );

                                 $parade = [
                                     'parade_name' => $liDom->filter('.onecontent .title')->text(),
                                     'parade_time' =>  $paradeTime,
                                     'parade_type' => $liDom->filter('.onecontent .format')->text()
                                 ];
                                 $paradeData[] = $parade;
                             }else if ($liDom->filter('.onecontent .title_disable')->count()  && $parent == $className) {
                                 $parade = [
                                     'parade_name' => $liDom->filter('.onecontent .title_disable')->text(),
                                     'parade_time' => $liDom->filter('.timer p')->eq(0)->text(),
                                     'parade_type' => $liDom->filter('.onecontent .format_disable')->text()
                                 ];
                                 $paradeData[] = $parade;

                             }
                         }catch (\Exception $e) {

                         }
                     });

                    $this->createParade($channelName, $date, $paradeData, __CLASS__, $url);
                });

            }

        });
    }

    /**
     * @return mixed
     */
    public function getUrlGroup()
    {
        $tasks = $this->getFutureTime(6, 'Y-m-d');
        array_walk($tasks, function(&$v) {
              $v['url'] = str_replace('{DATE}', $v['param'], $this->url);
        });

        return $tasks;
    }
}