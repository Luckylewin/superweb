<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/1
 * Time: 16:36
 */

namespace console\collectors\hlpus;

use console\collectors\common;
use Symfony\Component\DomCrawler\Crawler;
use Yii;
use yii\base\Model;

class HplusSearcher extends common
{

    public $model;


    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public $href = [];

    public function start()
    {
        $this->collectMovie();
    }


    protected function getHref($url)
    {
        $dom = $this->getDom($url);

        $tasks = [];
        $pageTotal = $dom->filter('.pagination li')->count();
        $pageTotal = $pageTotal == 0 ? 1 : $pageTotal;

        for ($page = 1; $page<=$pageTotal; $page++) {
            if ($page == 1) {
                $tasks[] = $url;
            } else {
                $tasks[] = "{$url}/{$page}";
            }
        }


        if (empty($tasks)) return false;

        $links = [];
        foreach ($tasks as $url) {
            $dom = $this->getDom($url);
            if ($dom == false) {
                continue;
            }

            // 从第一页开始读取数据
            $dom->filter('.panel-wrapper .image-wrapper')->each(function(Crawler $wrapper) use(&$links) {
                $tooltips = $wrapper->filter('.tooltips')->first();
                $links[] = "http://hplus.com.vn/".$tooltips->attr('href');
            });
            if ($this->getDomIsCached() == false) {
                echo "睡眠6s".PHP_EOL;
                sleep(6);
            }
        }

        return $links;
    }

    /**
     * 获取具有多集的 集数据
     * @param Crawler $dom
     * @param $url
     * @return array|bool
     */
    public function getEpisode(Crawler $dom, $url)
    {
        preg_match('/\d+/', basename($url), $match);
        if (empty($match)) {
            return false;
        }

        $vodId = $match[0];

        $lastEpisode = trim($dom->filter('#section-tab-1 .panel-wrapper h3')->first()->text());
        preg_match('/\d+/', $lastEpisode, $match);
        $lastEpisode = empty($match) ? false : $match[0];

        $episodes = [];
        if ($lastEpisode) {
            for($i=1; $i<$lastEpisode; $i++) {
                $episode = sprintf('%02d', $i);
                $data['url']     = "http://hplus.com.vn/en/content/detail/eps-{$episode}-{$vodId}.html";
                $data['episode'] = $i;
                $episodes[] = $data;
            }
        }

        return $episodes;
    }

    public function getProfile(Crawler $dom, $url)
    {
        $vod['url']    = $url;
        $vod['title']  = $dom->filter('title')->first()->text();
        $vod['image']  = $dom->filter(".featureImg-inner img")->first()->attr('src');

        $dom->filter('.overview-box .overview-item')->each(function(Crawler $item) use(&$vod){
            $span  = $item->filter('span');
            $field = trim($span->eq(0)->text());
            $value = trim($span->eq(1)->text());
            switch ($field)
            {
                case 'Genre':
                    $vod['vod_type'] = $value;
                    $vod['vod_keywords'] = $value;
                    break;
                case 'Actor':
                    $vod['vod_actor'] = $value;
                    break;
                case 'Director':
                    $vod['vod_director'] = $value;
                    break;
                case 'National':
                    $vod['vod_area'] = $value;
                    break;
                case 'Duration':
                    $vod['vod_length'] = $value;
                    break;
                case 'Release Year':
                    $vod['vod_year'] = $value;
                    break;
            }

        });

        $vod['info'] = trim($dom->filter('.description-detail-inner .content-inner')->first()->text());

        return $vod;
    }

    public function collectTv()
    {
        $tasks = [
            'http://hplus.com.vn/en/genre/index/7/2',
            'http://hplus.com.vn/en/genre/index/21/2'
        ];

        $links = [];
        foreach ($tasks as $link) {
            $links = array_merge($links, $this->getHref($link));
        }

        if (!empty($links)) {
            $total = count($links);
            foreach ($links as $key => $url) {
                echo "正在爬取({$key}/{$total}):{$url}>>>>>>>>>>>>>>>>>>>>".PHP_EOL;
                try {
                    $dom   = $this->getDom($url);
                    $data  = $this->getProfile($dom, $url);
                    $links = $this->getEpisode($dom, $url);
                } catch (\Exception $e) {
                    $this->debug($e);
                    continue;
                }
                $data['links'] = $links;

                if (method_exists($this->model, 'collect')) {
                    $this->model->collect($data, 'hplus');
                }

                if ($this->getDomIsCached() === false) {
                    $this->goSleep([5,7]);
                }
            }
        }
    }

    public function collectVariety()
    {
        $tasks = [
            'Gameshow'   => 'http://hplus.com.vn/en/genre/index/37/3',
            'REALITY TV' => 'http://hplus.com.vn/en/genre/index/21/2',
            'CULTURAL - EDUCATIONAL' => 'http://hplus.com.vn/en/genre/index/4/3'
        ];

        $links = [];
        foreach ($tasks as $link) {
            $links = array_merge($links, $this->getHref($link));
        }

        if (!empty($links)) {
            $total = count($links);
            foreach ($links as $key => $url) {
                echo "正在爬取({$key}/{$total}):{$url}>>>>>>>>>>>>>>>>>>>>".PHP_EOL;
                $dom   = $this->getDom($url);
                $data  = $this->getProfile($dom, $url);
                $links = $this->getEpisode($dom, $url);
                $data['links'] = $links;

                if (method_exists($this->model, 'collect')) {
                    $this->model->collect($data, 'hplus');
                }

                if ($this->getDomIsCached() === false) {
                    $this->goSleep([5,7]);
                }
            }
        }
    }

    public function collectCarton()
    {
        $tasks = [
            'http://hplus.com.vn/en/genre/index/163/2'
        ];

        $links = [];
        foreach ($tasks as $link) {
            $links = array_merge($links, $this->getHref($link));
        }

        if (!empty($links)) {
            $total = count($links);
            foreach ($links as $key => $url) {
                echo "正在爬取({$key}/{$total}):{$url}>>>>>>>>>>>>>>>>>>>>".PHP_EOL;
                $dom   = $this->getDom($url);
                $data  = $this->getProfile($dom, $url);
                $links = $this->getEpisode($dom, $url);
                $data['links'] = $links;

                if (method_exists($this->model, 'collect')) {
                    $this->model->collect($data, 'hplus');
                }

                if ($this->getDomIsCached() === false) {
                    $this->goSleep([5,7]);
                }
            }
        }
    }


    public function collectMovie()
    {
        $links = $this->getHref("http://hplus.com.vn/en/categories/movies");

        if (!empty($links)) {
            foreach ($links as $href) {
                echo "当前采集: {$href}";
                $dom = $this->getDom($href);
                if ($dom == false) {
                    continue;
                }
                $vod['url']    = $href;
                $vod['title']  = $dom->filter('title')->first()->text();
                $vod['image']  = $dom->filter(".featureImg-inner img")->first()->attr('src');

                $dom->filter('.overview-box .overview-item')->each(function(Crawler $item) use(&$vod){
                    $span  = $item->filter('span');
                    $field = trim($span->eq(0)->text());
                    $value = trim($span->eq(1)->text());
                    switch ($field)
                    {
                        case 'Genre':
                            $vod['vod_type'] = $value;
                            break;
                        case 'Actor':
                            $vod['vod_actor'] = $value;
                            break;
                        case 'Director':
                            $vod['vod_director'] = $value;
                            break;
                        case 'National':
                            $vod['vod_area'] = $value;
                            break;
                        case 'Duration':
                            $vod['vod_length'] = $value;
                            break;
                        case 'Release Year':
                            $vod['vod_filmtime'] = $value;
                            break;
                    }

                });

                $vod['info'] = trim($dom->filter('.description-detail-inner .content-inner')->first()->text());

                if (method_exists($this->model, 'collect')) {
                    $this->model->collect($vod, 'hplus');
                }

                if ($this->getDomIsCached() == false) {
                    echo "睡眠5s" . PHP_EOL;
                    sleep(5);
                }

            }
        }
    }

}