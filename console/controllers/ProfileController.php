<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/5/4
 * Time: 10:15
 */
namespace console\controllers;

use common\models\Vod;
use console\components\MySnnopy;
use Symfony\Component\DomCrawler\Crawler;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * 影片信息抓取控制器
 * Class ProfileController
 * @package console\controllers
 */
class ProfileController extends Controller
{
    private $url;
    private $profileItems;



    public function actionVod()
    {
        foreach (Vod::find()->where(['vod_cid' => '1'])->each(10) as $vod) {
            //判断是否已经抓取过
            if ($vod->vod_douban_id == 0) {
                $this->stdout("当前抓取：{$vod->vod_name}" . PHP_EOL);
                try{
                    $this->_getUrl($vod->vod_name);
                    $this->_crawlData($this->url);
                    $this->_updateVod($vod);
                    $this->_sleep();
                } catch (\Exception $e) {
                    $this->stdout($e->getMessage() . PHP_EOL);
                }
            } else {
                $this->stdout($vod->vod_name . "已经抓取" . PHP_EOL, Console::BG_GREY, Console::FG_BLUE);
            }

        }
    }

    private function _getUrl($name)
    {
        $url = "https://movie.douban.com/j/subject_suggest?q=" . $name;

        $snnopy = MySnnopy::init();
        $snnopy->fetch($url);

        if ($snnopy->results == false) {
            return $this->stdout('查询错误');
        }

        $queryData = json_decode($snnopy->results, true);

        if (!empty($queryData)) {
            throw new \Exception("查询错误");
        }

        $queryData = current($queryData);

        if (!isset($queryData['url'])) {
            throw new \Exception("查询错误");
        }

        return $this->url = $queryData['url'];
    }


    private function _crawlData($url)
    {
        $snnopy = MySnnopy::init();
        $snnopy->fetch($url);
        $vodDetail = $snnopy->results;
        \Yii::$app->cache->set('vod-detail', $vodDetail);

        if ($vodDetail == false) {
            return $this->stdout('获取资料错误');
        }

        $fields = [
            '导演' => 'vod_director',
            '主演' => 'vod_actor',
            '类型:' => 'vod_keywords',
            '制片国家/地区:' => 'vod_area',
            '上映日期' => 'vod_filmtime',
            '语言:' => 'vod_language',
            '片长:' => 'vod_length',
            '又名:' => 'vod_title'
        ];

        $crawler = new Crawler();
        $crawler->addHtmlContent($vodDetail, 'UTF-8');

        $profile = $crawler->filter('div.subject');

        $items = [];

        $profileItems = [];

        //基本数据
        $profile->filter('div#info')->each(function(Crawler $node) use(&$items, $crawler) {

            $node->filter('span.pl')->each(function(Crawler $node) use (&$items, $crawler) {
                if ($next = $node->nextAll()) {
                    $text = $node->text();
                    $items[$text] = $next->text();
                    if ( $next->nodeName() != 'span') {
                        $items[$text]  = $crawler->filterXPath('//div[@id="info"]')
                                                 ->filterXPath("//span[contains(./text(), '$text')]/following::text()[1]")
                                                 ->text();
                    }
                }

            });

        });

        //填充转换
        foreach ($items as $field => $item) {
            if (isset($fields[$field])) {
                $key = $fields[$field];
                $profileItems[$key] = $item;
            }
        }

        //海报图片
        $profileItems['vod_pic'] = $profile->filter('div#mainpic')->filter('img')->attr('src');
        //剧情
        $profileItems['vod_scenario'] = $crawler->filter('div#link-report')->text();
        //简介
        $profileItems['vod_content'] = $crawler->filter('div#link-report')->text();

        //豆瓣id
        $crawler->filter('a.nbgnbg')->each(function (Crawler $node) use (&$profileItems) {
            preg_match('/(?<=subject\/)\d+/', $node->attr('href'), $id);
            if (isset($id[0]) && $id[0]) $profileItems['vod_douban_id'] = $id[0];
        });

        //豆瓣评分
        $crawler->filter('strong.rating_num')->each(function (Crawler $node) use(&$profileItems) {
            $profileItems['vod_douban_score'] = $node->text() ? $node->text() : '0.0';
        });

        //格式整理
        array_walk($profileItems, function(&$v, $k) {
            $v = preg_replace('/\s+/','', $v);
        });


        return $this->profileItems = $profileItems;
    }

    private function _sleep()
    {
        $time = mt_rand(6,12);
        $this->stdout("------------进入睡眠 $time s------------" . PHP_EOL);
        sleep($time);
    }

    private function _updateVod(Vod $vod)
    {
        //更新赋值
        foreach ($this->profileItems as $field => $value) {
            if ( ($vod->$field == 0 || empty($vod->$field)) && !empty($value)) {
                $vod->$field = $value;
            }
        }
        $vod->save(false);
        print_r($this->profileItems);
        $this->url = '';
        $this->profileItems = [];
    }

}