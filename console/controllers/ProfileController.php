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

class ProfileController extends Controller
{
    private $url;
    private $profileItems;

    public function actionVod()
    {
        foreach (Vod::find()->where(['vod_cid' => '1'])->each(10) as $vod) {
            //判断是否已经抓取过
            if ($vod->vod_douban_id == false) {
                $this->stdout("当前抓取：{$vod->vod_name}" . PHP_EOL);
                $this->_getUrl($vod->vod_name);
                $this->_crawlData($this->url);
                $this->_updateVod($vod);

                $this->_sleep();
            }

        }
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
            $vod->$field = $value;
        }
        $vod->save(false);
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
        $queryData = current($queryData);

        if (isset($queryData['url'])) {
            return $this->url = $queryData['url'];
        }

        return false;
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
        $nodeText = [];
        $profileItems = [];

        //基本数据
        $profile->filter('div#info')->each(function(Crawler $node, $i) use(&$items, &$nodeText) {

            $node->filterXPath('//text()')->each(function(Crawler $node, $i) use (&$nodeText) {
                if (trim($node->text())) $nodeText[] = trim($node->text());
            });
            $node->filter('span.pl')->each(function(Crawler $node, $i) use (&$items) {
                if ($next = $node->nextAll()) $items[$node->text()] = $next->text();
            });

        });

        //填充转换
        foreach ($items as $field => $item) {
            if (isset($fields[$field])) {
                $key = $fields[$field];
                if ( in_array($key, ['vod_language', 'vod_area', 'vod_title']) ) {
                    $nodeKey = array_search($field, $nodeText) + 1;
                    if (isset($nodeText[$nodeKey])) {
                        $item = $nodeText[$nodeKey];
                    }
                }
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

}