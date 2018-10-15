<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:48
 */

namespace console\collectors\parade;

use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use yii\helpers\ArrayHelper;

class vn extends CommonParade
{

    public $tomorrow;

    /*
        *节目预告
    */
    public function start()
    {
        $this->getviebao();
        $this->getHtvOne();
        //$this->getHtvTwo();
    }

    //vietbao
    public function getviebao()
    {
        $data = [
            'vtv1' => 'http://tv.vietbao.vn/lich-phat-song/vtv1/ngay-',
            'vtv2' => 'http://tv.vietbao.vn/lich-phat-song/vtv2/ngay-',
            'vtv3' => 'http://tv.vietbao.vn/lich-phat-song/vtv3/ngay-',
            'vtv4' => 'http://tv.vietbao.vn/lich-phat-song/vtv4/ngay-',
            'vtv5' => 'http://tv.vietbao.vn/lich-phat-song/vtv5/ngay-',
            'vtv6' => 'http://tv.vietbao.vn/lich-phat-song/vtv6/ngay-',
            'vtv7' => 'http://tv.vietbao.vn/lich-phat-song/vtv7/ngay-',
            'vtv8' => 'http://tv.vietbao.vn/lich-phat-song/vtv8/ngay-',
            'Ha Noi 1' => 'http://tv.vietbao.vn/lich-phat-song/hanoitv-1/ngay-',
            'Ha Noi 2' => 'http://tv.vietbao.vn/lich-phat-song/hanoitv-2/ngay-',
            'BTV1'  => 'http://tv.vietbao.vn/lich-phat-song/truyen-hinh-binh-duong-btv1/ngay-',
            'BTV2'  => 'http://tv.vietbao.vn/lich-phat-song/truyen-hinh-binh-duong-btv2/ngay-',
            'thvl2' => 'http://tv.vietbao.vn/lich-phat-song/truyen-hinh-vinh-long-thvl2/ngay-',
            'dan2'  => 'http://tv.vietbao.vn/lich-phat-song/truyen-hinh-dong-nai-dn2/ngay-',
            'htv2'  => 'http://tv.vietbao.vn/lich-phat-song/htv2/ngay-',
            'htv3'  => 'http://tv.vietbao.vn/lich-phat-song/htv3/ngay-',
            'htv4'  => 'http://tv.vietbao.vn/lich-phat-song/htv4/ngay-',
            'htv7'  => 'http://tv.vietbao.vn/lich-phat-song/htv7/ngay-',
            'htv9'  => 'http://tv.vietbao.vn/lich-phat-song/htv9/ngay-',
        ];

        $start = strtotime('today');

        foreach ($data as $key => $url) {
            for ($i=0; $i<5; $i++) {
                $actualUrl = $url . date('d-m-Y', $start + $i * 86400) . '.html';
                $this->crawlViebao($key, $actualUrl);
                $this->_sleep(4, 8);
            }

        }

    }

    /**
     * snnopy分析viebao
     * @param $channelName
     * @param $url
     * @return bool
     */
    public function crawlViebao($channelName, $url)
    {
        $snnopy = MySnnopy::init();
        $snnopy->fetch($url);
        $str = $snnopy->results;

        //获取当前日期
        $crawler = new Crawler();
        $crawler->addHtmlContent($str, 'UTF-8');
        $date = $crawler->filter('div.head-title')->filter('ul.items .active')->eq(0)->text();

        try {
            $date = explode(" ", trim($date));
            $date = explode('/', end($date));
            $date = date('Y') . '-' . implode( '-', array_reverse($date));
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }

        $paradeData = [];
        $crawler->filter('div.chanel-detail')->last()->filter('div.row')->each(function(Crawler $node,$i) use (&$paradeData) {
            $item = [];
            $item['parade_time'] = $node->filter('div.time-tv')->eq(0)->text();
            $item['parade_name'] = $node->filter('div.t-detail a span')->eq(0)->text();
            $paradeData[] = $item;
        });

        //查找频道是否存在
        $this->createParade($channelName, $date, $paradeData, __CLASS__, $url);

        return true;
    }


    public function getHtvTwo()
    {
        // 获取时间表
        $url = 'http://hplus.com.vn/content/ajax_schedule/';
        $snoopy = MySnnopy::init();
        $snoopy->fetch($url);

        if (!$snoopy->results) {
             echo "没有数据返回";
        }

        $dates = [];
        $crawler = new Crawler();
        $crawler->addHtmlContent($snoopy->results, 'UTF-8');
        $crawler->filter('option')->each(function(Crawler $node) use (&$dates) {
            $dates[] = ['num' => $node->attr('value'), 'date' => date('Y-m-d', strtotime($node->text()))];
        });


        $schedule = [
            'htv2' => ['id' => 'http://hplus.com.vn/xem-kenh-htv2-2630.html'],
            'thao' => ['id' => 'http://hplus.com.vn/xem-kenh-htv-the-thao-hd-4009.html']
        ];

        foreach ($schedule as $channelName => $value) {
            foreach ($dates as $date) {

                $snoopy = MySnnopy::init();
                $form['id'] = $value['id'];
                $form['num'] = $date['num'];
                $snoopy->submit($url, $form);

                if ($snoopy->results) {
                    $todayFirst = $this->tomorrow;
                    $data = $this->crawlTypeTwo($snoopy->results);
                    $todayLast = $this->splitDay($data);

                    if (!empty($todayFirst)) {
                        $data = ArrayHelper::merge($todayFirst, $todayLast);
                    } else {
                        $data = $todayLast;
                    }

                    if ($data) {
                        //查找频道是否存在
                        $this->createParade($channelName, $date, $data, '122',  $value['id']);
                    }
                }
            }
        }

    }


    public function splitDay($data)
    {
        $this->tomorrow = [];
        $tomorrowFlag = false;

        $today = [];
        foreach ($data as $key => $value) {
            if ($key > 0 && isset($data[$key-1])) {
                $prev = strstr($data[$key-1]['parade_time'], ':', true);
                $curr = strstr($value['parade_time'], ':', true);

                if ($prev > $curr) {
                    $tomorrowFlag = true;
                }

                if ($tomorrowFlag) {
                    $this->tomorrow[] = $value;
                } else {
                    $today[] = $value;
                }
            }
        }

        return $today;
    }

    public function getHtvOne()
    {
        $data = [
            'htv9' => ['url' => 'http://hplus.com.vn/xem-kenh-htv9-hd-2667.html', 'id' => '2667'],
            'htv7' => ['url' => 'http://hplus.com.vn/xem-kenh-htv7-hd-256.html', 'id' => '2667'],
            'htv3' => ['url' => 'http://hplus.com.vn/xem-kenh-htv3-2535.html', 'id' => '2535'],
        ];

        $snoopy = MySnnopy::init();

        foreach ($data as $channelName => $value) {
            $date = '2018-08-28';
            $start = strtotime('yesterday');

            for ($day = 0; $day <= 6; $day++) {
                $form['contentId'] = $value['id'];
                $form['date'] = date('Y-m-d', $start + 86400 * $day );
                $snoopy->submit('http://hplus.com.vn/content/getcatchup_db/', $form);
                $data = $this->crawlTypeOne($snoopy->results);
                if ($data) {
                    //查找频道是否存在
                    $this->createParade($channelName, $date, $data, __CLASS__,  $value['url']);
                }
            }

        }
    }

    private function crawlTypeOne($content)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($content, 'UTF-8');
        $parade = [];
        $crawler->filter('div.meta')->each(function(Crawler $node, $i) use (&$parade) {
            try {
                $time =  substr(trim(strstr($node->filter('span.time')->text(), '-', true)), 0, 5);
                $content = $node->filter('h3.title')->text();
                $parade[] = [
                    'parade_time' => $time,
                    'parade_name' => $content
                ];
                } catch (\Exception $e) {

            }
        });

        return $parade;
    }

    private function crawlTypeTwo($content)
    {
        if ($content == false) {
            return false;
        }
        $crawler = new Crawler();
        $crawler->addHtmlContent($content, 'UTF-8');
        $parade = [];
        $crawler->filter('div.lps')->each(function(Crawler $node, $i) use (&$parade) {
            try {
                $time =  trim($node->filter('span')->text());
                $content = trim(str_replace($time, '', $node->text()));
                $parade[] = [
                    'parade_time' => $time,
                    'parade_name' => $content
                ];
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        });

        return $parade;
    }

    public function getTvSouData($channelCatagory)
    {
        //https://www.tvsou.com/epg/ZJTV-1?class=weishi
        //https://www.tvsou.com/epg/yangshi

    }


}