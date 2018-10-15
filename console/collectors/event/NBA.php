<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/8
 * Time: 11:12
 */

namespace console\collectors\event;

use console\models\parade\collector;
use Symfony\Component\DomCrawler\Crawler;

class NBA extends common implements collector
{

    public $url = 'https://www.zhibo8.cc/index2.htm';

    public function start()
    {
        $this->getPage();
    }

    public function getPage()
    {
        $dom = $this->getDom($this->url);
        $data = [];

        $dom->filter('.box')->each(function(Crawler $table) use(&$data) {
            // 比赛日期
            $date = trim($table->filter('.titlebar h2')->eq(0)->attr('title'));

            // 比赛信息
            $table->filter('.content li')->each(function (Crawler $li) use (&$data,$date) {
                $label = $li->attr('label');
                if (strpos($label, 'NBA') !== false) {
                    $text = $li->filter('b')->text();
                    if (strpos($text, 'NBA常规赛') !== false) {

                        preg_match('/.*-\s+\S+/', $li->text(), $match);
                        if (isset($match[0])) {
                            $text = str_replace([' - ', ' NBA常规赛'], ['-',''], $match[0]);
                            print_r($text);

                            $text = explode(' ', $text);
                            $teams = explode('-', $text[1]);

                            $data[] = [
                               'time' => $this->convertTimeZone($date . " " . $text[0], 'timestamp', 8, 8),
                               'teams' => [
                                   'teamA' => $teams[0],
                                   'teamB' => $teams[1]
                               ]
                            ];
                        }
                    }
                }
            });
        });

        if (!empty($data)) {
            foreach ($data as $val) {
                try {
                    $this->createMajorEvent("NBA常规赛", 'NBA常规赛' , $val['time'], $val['teams']);
                }catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

    }
}