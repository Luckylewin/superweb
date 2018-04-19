<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:48
 */

namespace console\models\parade;

use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use backend\models\ChannelIptv;
use backend\models\Parade;

class vn extends CommonParade
{
    /*
        *节目预告
    */
    public function start()
    {
        $this->getviebao();//viebao
        //$this->getChinaChannel("yangshi"); //央视
        //$this->getChinaChannel("weishi");  //卫视
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

        foreach ($data as $key => $url) {
            echo "get the parade of $key" ,PHP_EOL;
            $this->crawlViebao($key, $url);
            sleep(4);
        }

        /*
         * $channelName = 'vtv1';
            $url = $data['vtv1'];
            if (0 && !$str = \Yii::$app->cache->get('vtv1')) {
            $snnopy = MySnnopy::init();
            $snnopy->fetch($url);
            $str = $snnopy->results;
            \Yii::$app->cache->set('vtv1', $str);
        }*/


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
            $paradeData[]['parade_time'] = $node->filter('div.time-tv')->eq(0)->text();
            $paradeData[]['parade_name'] = $node->filter('div.t-detail a span')->eq(0)->text();
        });

        //查找频道是否存在
        $channel = ChannelIptv::findOne(['name' => $channelName]);
        if (is_null($channel) || !is_null(Parade::findOne(['channel_name' => $channelName, 'parade_date' => $date])) || empty($paradeData)) {
            echo "没有这个频道|已经存在|没有预告";
            return false;
        }

        $parade = new Parade();
        $parade->parade_date = $date;
        $parade->parade_data = json_encode($paradeData);
        $parade->channel_id = $channel->ID;
        $parade->channel_name = $channel->name;
        $parade->save(false);

        return true;
    }

    //htvplus
    public function getChannelParade()
    {
        $data = [
            'htv3' => 'http://hplus.com.vn/xem-kenh-htv3-2535.html',
            'htv7' => 'http://hplus.com.vn/xem-kenh-htv7-sd-10036.html',
            'htv9' => 'http://hplus.com.vn/xem-kenh-htv9-sd-10037.html',
            'THUAN VIET' => 'http://hplus.com.vn/xem-kenh-htvc-thuan-viet-2396',
            'htv4' => 'http://hplus.com.vn/xem-kenh-htv4-2528.html',
            'htv2' => 'http://hplus.com.vn/xem-kenh-htv2-hd-2669.html'
        ];

    }



    public function getTvSouData($channelCatagory)
    {
        //https://www.tvsou.com/epg/ZJTV-1?class=weishi
        //https://www.tvsou.com/epg/yangshi

    }


}