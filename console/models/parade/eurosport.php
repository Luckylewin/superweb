<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/31
 * Time: 11:04
 */

namespace console\models\parade;

use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use Yii;

class eurosport extends CommonParade implements collector
{

    public $url = 'https://www.eurosport.com/tvschedule.shtml';
    public $debug = false;

    /**
     *
     */
    public function start()
    {
       $this->getOneWeek();
    }

    public function getOneWeek()
    {
        $data = Yii::$app->cache->get('eurosport');
        if ($data == false && $this->debug == false) {
            $snnopy = MySnnopy::init();
            $snnopy->fetch($this->url);
            $data = $snnopy->results;
            Yii::$app->cache->set('eurosport', $data);
        }

        $dom = new Crawler();
        $dom->addHtmlContent($data);

        $dom->filter('.tv-day')->each(function(Crawler $node) {
            $program = $this->initProgram();
            $date = $node->attr('data-date');
            $date = strstr($date, 'T', true);

            if (strtotime($date) < strtotime(date('Y-m-d'))) {
                return false;
            }

            $node->filterXPath('//div[@data-ch-id]')->each(function(Crawler $divDom) use(&$program) {
                $parade = [
                    'parade_name' => $divDom->filter('.tv-program__title')->text(),
                    'parade_time' => date('H:i', strtotime($divDom->filter('.tv-program__tile-time')->text()))
                ];
                //判断是节目1还是节目2
                $key = $divDom->attr('data-ch-id') == '0' ? 0 : 1;
                $program[$key]['parade'][] = $parade;
            });

            foreach ($program as $value) {
                $this->createParade($value['name'], $date,$value['parade'], __CLASS__, $this->url);
            }

        });
    }

    public function initProgram()
    {
        return [
            ['name' => 'EURO SPORT 1', 'parade'=>[]],
            ['name' => 'EURO SPORT 2', 'parade'=>[]]
        ];
    }

    /**
     *
     */
    public function getUrlGroup()
    {
    }


}