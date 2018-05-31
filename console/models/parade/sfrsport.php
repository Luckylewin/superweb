<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/31
 * Time: 9:40
 */

namespace console\models\parade;

use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use Yii;

class sfrsport extends CommonParade implements collector
{

    public $url = 'http://tv.sfr.fr/epg/maintenant/{DATE}/SFR%20Sport-51601/';
    public $debug = true;

    public function start()
    {
        $tasks = $this->_getUrlGroup();
        foreach ($tasks as $task) {
            $this->getOneDay($task['date'], $task['url']);
            $this->_sleep(2,5);
        }
    }

    /**
     * @param $date
     * @param $url
     */
    public function getOneDay($date, $url)
    {
        $data = Yii::$app->cache->get('sfr');
        if ($data == false || $this->debug == false) {
            $snnopy = MySnnopy::init();
            $snnopy->fetch($url);
            $data = $snnopy->results;
            $data = Yii::$app->cache->set('sfr', $data);
        }

        $dom = new Crawler();
        $dom->addHtmlContent($data);

        $program = $this->initProgram();

        $dom->filter('.epg_ligne_chaine ')->each(function(Crawler $node, $i) use(&$program) {

            $program[$i]['parade'] = $node->filter('.epg_element_prog')->each(function (Crawler $divDom) {
                $timeStart = $divDom->filter('.heure_prog')->text();
                $programName = $divDom->filter('.lib_prog')->text();
                $during = $divDom->filter('.duree_prog')->text();
                $type = $divDom->filter('.genre_prog')->text();
                return [
                    'parade_time' => $timeStart,
                    'parade_name' => $programName,
                    'parade_type' => $type,
                    'parade_during' => $during
                ];
            });

        });

        foreach ($program as $value) {
            if (!empty($value['parade'])) {
                $this->createParade($value['name'], $date, $value['parade']);
            }
        }
    }


    /**
     * @return array
     */
    public function initProgram()
    {
        $program = [
            ['name' => 'SFR Sport 1','parade'=>[]],
            ['name' => 'SFR Sport 2','parade'=>[]],
            ['name' => 'SFR Sport 3','parade'=>[]],
            ['name' => 'SFR Sport 5','parade'=>[]],
        ];

        return $program;
    }

    /**
     * @return mixed
     */
    public function _getUrlGroup()
    {
        $tasks = $this->getFutureTime(6, 'Ymd');
        array_walk($tasks, function(&$v) {
             $v['url'] = str_replace('{DATE}', $v['param'], $this->url);
        });

        return $tasks;
    }

}