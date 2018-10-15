<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/31
 * Time: 9:40
 */

namespace console\collectors\parade;

use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use Yii;

class sfrsport extends CommonParade implements collector
{
    //需要验证服务器访问是否不同的返回
    public $url = 'http://tv.sfr.fr/epg/maintenant/{DATE}/SFR%20Sport-51601/';
    public $debug = true;

    public function start()
    {
        $tasks = $this->_getUrlGroup();
        if (empty($tasks)) {
            echo "SKIP: 数据库中存在所有来源为" . $this->getClassName(__CLASS__) . "的节目" , PHP_EOL;
            return;
        }

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
        $snnopy = MySnnopy::init();
        $snnopy->fetch($url);
        $data = $snnopy->results;

        $dom = new Crawler();
        $dom->addHtmlContent($data);

        $program = $this->initProgram();

        $dom->filter('.epg_ligne_chaine ')->each(function(Crawler $node, $i) use(&$program, $date) {

            $program[$i]['parade'] = $node->filter('.epg_element_prog')->each(function (Crawler $divDom, $index) use($date) {
                $timeStart = $divDom->filter('.heure_prog')->text();
                $programName = $divDom->filter('.lib_prog')->text();
                $during = $divDom->filter('.duree_prog')->text();
                $type = $divDom->filter('.genre_prog')->text();
                $start_day = strtotime($divDom->filter('.date_deb_prog')->text());
                $end_day = strtotime($divDom->filter('.date_fin_prog')->text());

                //判断是否包含了下一天的数据
                if ($start_day == $end_day) {
                    $parade_date = $date  . ' ' . $timeStart;
                } elseif ($start_day < $end_day) { //+ 1day
                    $parade_date = date('Y-m-d', strtotime('+1 day', strtotime($date)))  . ' ' . $timeStart;
                } else {
                    $parade_date = date('Y-m-d', strtotime('-1 day', strtotime($date)))  . ' ' . $timeStart;
                }

                return [
                    'parade_time' => $timeStart,
                    'parade_name' => $programName,
                    'parade_type' => $type,
                    'parade_during' => $during,
                    'parade_timestamp' => $this->convertTimeZone($parade_date, "timestamp", 0, 8)
                ];
            });

        });

        foreach ($program as $value) {
            if (!empty($value['parade'])) {
                $this->createParade($value['name'], $date, $value['parade'], __CLASS__, $url);
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

        $tasks = $this->getFinalTasks($tasks, $this->getClassName(__CLASS__), 'source');

        return $tasks;
    }

}