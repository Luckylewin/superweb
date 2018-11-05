<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/1
 * Time: 16:36
 */

namespace console\collectors\hlpus;

use console\collectors\common;
use GuzzleHttp\Cookie\CookieJar;
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
        $dom = $this->getHtml($url);

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
            $dom = $this->getHtml($url);
            if ($dom == false) {
                continue;
            }

            // 从第一页开始读取数据
            $dom->filter('.panel-wrapper .image-wrapper')->each(function(Crawler $wrapper) use(&$links) {
                $tooltips = $wrapper->filter('.tooltips')->first();
                $links[] = "http://hplus.com.vn/".$tooltips->attr('href');
            });

            if ($this->getDomIsCached() == false) {
                $this->goSleep(6);
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
        if ($dom->filter('#section-tab-1 .panel-wrapper h3')->count()) {
            $lastEpisode = trim($dom->filter('#section-tab-1 .panel-wrapper h3')->first()->text());
            preg_match('/\d+/', $lastEpisode, $match);
            $lastEpisode = empty($match) ? false : $match[0];

            $episodes = [];
            /*if ($lastEpisode) {
                for($i=1; $i<=$lastEpisode; $i++) {
                    $episode = sprintf('%02d', $i);
                    $data['url']     = "http://hplus.com.vn/en/content/detail/eps-{$episode}-{$vodId}.html";
                    $data['episode'] = $i;
                    $episodes[] = $data;
                }
            }*/

            // 算一下有多少页
            $pageTotal = ceil($lastEpisode/6);
            for ($page=$pageTotal; $page>=1; $page--) {
                $pageLink = "http://hplus.com.vn/vi/content/episode/{$vodId}/{$vodId}/2/{$page}";
                $linkDom  = $this->getHtml($pageLink);
                $linkDom->filter('#section-tab-1 .panel-wrapper')->each(function(Crawler $wrapper) use (&$episodes, $vodId) {
                    $episode = $wrapper->filter('h3')->first()->text();
                    $url     = $wrapper->filter('a')->first()->attr('href');
                    preg_match('/\d+/', $episode, $match);
                    $episode = empty($match) ? false : $match[0];
                    $pic     = $wrapper->filter('img')->first()->attr('src');

                    $episodes[] = [
                         'pic'     => $pic,
                         'episode' => $episode,
                         'url'     => $url,
                    ];
                });

                if ($this->getDomIsCached() == false) {
                    $this->goSleep([2,3]);
                }
            }



        } else {
            $episodes[] = [
                'url' => $url,
                'episode' => 1
            ];
        }

        return $episodes;
    }

    public function getProfile(Crawler $dom, $url)
    {
        $vod['url']    = $url;
        $vod['title']  = str_replace(['Xem Phim ', 'Xem Show'],['', ''],$dom->filter('title')->first()->text());
        $vod['image']  = $dom->filter(".featureImg-inner img")->first()->attr('src');

        $dom->filter('.overview-box .overview-item')->each(function(Crawler $item, $i) use(&$vod){
            $span  = $item->filter('span');
            $field = trim($span->eq(0)->text());
            $md5   = substr(md5($field),0,8);
            $value = trim($span->eq(1)->text());

            switch ($md5)
            {
                case '59970100':
                    $vod['vod_type'] = $value;
                    break;
                case '9703372c':
                    $vod['vod_actor'] = $value;
                    break;
                case '09c1174c':
                    $vod['vod_director'] = $value;
                    break;
                case '5e1dc492':
                    $vod['vod_area'] = $value;
                    break;
                case '77d9bdc6':
                    $vod['vod_length'] = $value;
                    break;
                case '4259bb47':
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
            'http://hplus.com.vn/phim-bo/phim-hanh-dong',
            'http://hplus.com.vn/phim-bo/phim-tinh-cam'
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
            'Gameshow'   => 'http://hplus.com.vn/tv-show/chuong-trinh-thuc-te',
            'REALITY TV' => 'http://hplus.com.vn/vi/genre/index/161/3',
            'CULTURAL - EDUCATIONAL' => 'http://hplus.com.vn/tv-show/giao-duc'
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
                    $this->goSleep(3);
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
        $links = $this->getHref("http://hplus.com.vn/phim-le");

        if (!empty($links)) {
            foreach ($links as $href) {
                echo "当前采集: {$href}".PHP_EOL;
                $dom = $this->getHtml($href);
                if ($dom == false) continue;

                $vod = $this->getProfile($dom, $href);

                if (method_exists($this->model, 'collect')) {
                    $this->model->collect($vod, 'hplus');
                }
                if ($this->getDomIsCached() == false) {
                   $this->goSleep(5);
                }

            }
        }
    }

    public function getHtml($url)
    {
        $domain  = 'http://hplus.com.vn';
        $cookies = [
            'language' => 'vi'
        ];

        $jar = new CookieJar();
        $cookieJar = $jar->fromArray($cookies, $domain);

        return $this->getDom($url, 'html', 'UTF-8', $cookieJar);
    }

}