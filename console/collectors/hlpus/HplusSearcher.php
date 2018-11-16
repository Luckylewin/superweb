<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/1
 * Time: 16:36
 */

namespace console\collectors\hlpus;

use common\components\Func;
use console\collectors\common;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;
use yii\base\Model;

class HplusSearcher extends common
{
    public $model;

    protected $jar;

    public function getJar()
    {
        if (empty($this->jar)) {
            return $this->jar = new CookieJar();
        }

        return $this->jar;
    }

    public function __construct(Model $model)
    {
        ini_set('memory_limit', '100M');
        $this->model = $model;
    }

    public $href = [];

    public function collectAll()
    {
        $this->collectTv();
        $this->collectVariety();
        $this->collectMovie();
    }

    public function collectVariety()
    {
        $tasks = [
            'Gameshow'   => 'http://hplus.com.vn/tv-show/chuong-trinh-thuc-te',
            'REALITY TV' => 'http://hplus.com.vn/vi/genre/index/161/3',
            'CULTURAL - EDUCATIONAL' => 'http://hplus.com.vn/tv-show/giao-duc'
        ];

        $this->doCollect($tasks);
    }

    public function collectCarton()
    {
        $tasks = [
            'http://hplus.com.vn/en/genre/index/163/2'
        ];

        $this->doCollect($tasks);
    }

    public function collectTv()
    {
        $tasks = [
            'http://hplus.com.vn/phim-bo/phim-hanh-dong',
            'http://hplus.com.vn/phim-bo/phim-tinh-cam'
        ];

        $this->doCollect($tasks);
    }

    public function collectMovie()
    {
        $links = $this->getHref("http://hplus.com.vn/phim-le");

        if (!empty($links)) {
            $total = count($links);
            foreach ($links as $key => $href) {
                $this->color("正在爬取({$key}/{$total}):{$href} >>>>>>>>>>>>>>>>>>>>");
                $dom = $this->getHtml($href);
                if ($dom == false) {
                    $this->color("dom获取失败", 'error');
                    continue;
                }

                $vod = $this->getProfile($dom, $href);
                $vod['links'] = [['url' => $vod['url']]];

                $this->createVod($vod);
                $this->goSleep(5);

            }
        }
    }

    protected function doCollect($goalLinks)
    {
        $links = [];
        foreach ($goalLinks as $link) {
            $links = array_merge($links, $this->getHref($link));
        }

        if (!empty($links)) {
            $links = array_reverse($links);
            $total = count($links);
            foreach ($links as $key => $url) {
                $this->color("正在爬取({$key}/{$total}):{$url} >>>>>>>>>>>>>>>>>>>>");

                $dom   = $this->getDom($url);
                if ($dom) {
                    $data  = $this->getProfile($dom, $url);
                    $links = $this->getEpisode($dom, $url);

                    $data['links'] = $links;

                    $this->createVod($data);
                    $this->goSleep([3,6]);
                }
            }
        }
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

            $this->goSleep(6);
        }

        return $links;
    }

    /**
     * 获取具有多集的 集数据
     * @param Crawler $dom
     * @param $url
     * @return array|bool
     */
    protected function getEpisode(Crawler $dom, $url)
    {
        preg_match('/\d+/', basename($url), $match);
        if (empty($match)) {
            return false;
        }

        $vodId = $match[0];
        if ($dom->filter('#section-tab-1')->count()) {
            $episodes = [];

            if ($dom->filter('.section-articles .last')->count()) {
                $lastPageButton = $dom->filter('.section-articles .last')->first()->html();
                preg_match("/(?<=ajax_get\(')[^']+/", $lastPageButton, $lastPageLink);
            }

            if ($dom->filter('.section-articles .pagination li')->count()) {
                $pageButton = $dom->filter('.section-articles .pagination li')->last()->html();
                preg_match("/(?<=ajax_get\(')[^']+/", $pageButton, $PageLink);
            }

            if (isset($lastPageLink) && !empty($lastPageLink)) {
                $lastPageLink = explode('/', $lastPageLink[0]);
                $pageTotal    = end($lastPageLink);
            } else if (isset($pageLink) && !empty($pageLink)) {
                $pageLink     = explode('/', $pageLink[0]);
                $pageTotal    = end($pageLink);
            } else {
                $pageTotal    = 1;
            }

            for ($page=$pageTotal; $page>=1; $page--) {
                $pageLink = "http://hplus.com.vn/vi/content/episode/{$vodId}/{$vodId}/2/{$page}";
                $linkDom  = $this->getHtml($pageLink);
                $linkDom->filter('#section-tab-1 .panel-wrapper')->each(function(Crawler $wrapper) use (&$episodes, $vodId) {
                    $episode = $wrapper->filter('h3')->first()->text();
                    $url     = $wrapper->filter('a')->first()->attr('href');
                    preg_match('/\d+/', $episode, $match);
                    $episodeNum = empty($match) ? false : $match[0];
                    $pic     = $wrapper->filter('img')->first()->attr('src');

                    $episodes[] = [
                         'pic'     => $pic,
                         'episode' => $episodeNum,
                         'title'   => $episode,
                         'url'     => $url,
                    ];
                });

                $this->goSleep([2,3]);
            }

        } else {
            $episodes[] = [
                'url' => $url,
                'episode' => 1
            ];
        }

        return $episodes;
    }

    protected function getProfile(Crawler $dom, $url)
    {
        $vod['url']    = $url;
        $vod['title']  = str_replace(['Xem Phim ', 'Xem Show'],['', ''],$dom->filter('title')->first()->text());
        $vod['image']  = $dom->filter(".featureImg-inner img")->first()->attr('src');
        $vod['vod_origin_url'] = $url;

        $dom->filter('.overview-box .overview-item')->each(function(Crawler $item, $i) use(&$vod){
            $span  = $item->filter('span');
            $field = trim($span->eq(0)->text());
            $md5   = substr(md5($field),0,8);
            $value = trim($span->eq(1)->text());

            $genres = [
                ['pattern' => '/Võ Thuật/', 'value'   => 'Action'],
                ['pattern' => '/Phiêu Lưu/', 'value'   => 'Adventure'],
                ['pattern' => '/Hình Sự/',   'value'   => 'Crime'],
                ['pattern' => '/Hài Hước|Hình sự/',  'value'   => 'Humorous'],
                ['pattern' => '/Cổ Trang/', 'value'    => 'Costume'],
                ['pattern' => '/Ma Kinh Dị/', 'value'  => 'Hover'],
                ['pattern' => '/Khoa Học/', 'value'    => 'Science'],
                ['pattern' => '/Khoa Học Viễn Tưởng/', 'value'   => 'Science'],
                ['pattern' => '/Tình Cảm/', 'value'   => 'Romance'],
                ['pattern' => '/Chiến Tranh/', 'value'   => 'War'],
                ['pattern' => '/Gia Đình/', 'value'   => 'Family'],
                ['pattern' => '/Hoạt Hình/', 'value'   => 'Animation'],

            ];

            switch ($md5)
            {
                case '59970100':
                    $type = [];
                    foreach ($genres as $pattern) {
                        if (preg_match($pattern['pattern'], $value)) {
                            $type[] = $pattern['value'];
                        }
                    }
                    if (empty($type)) {
                        $vod['vod_type'] = 'Other';
                    } else {
                        $vod['vod_type'] = implode(',', $type);
                    }

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

    protected function getHtml($url)
    {
        $domain  = 'http://hplus.com.vn';
        $cookies = [
            'language' => 'vi'
        ];

        $cookieJar = $this->getJar()->fromArray($cookies, $domain);

        return $this->getDom($url, 'html', 'UTF-8', $cookieJar);
    }

    public function createVod($data)
    {
        //判断链接是不是空的
        if (!empty($data['links']) && method_exists($this->model, 'collect')) {
            // 判断链接第一个 是什么
            $link = $data['links'][0]['url'];
            $dom = $this->getDom($link);

            if (preg_match('/youtube.com\/watch/', $dom->html())) {
                $this->color("Youtube源" ,'info');
                $links = $data['links'];
                // 如果是youtube 源 需要重新获取链接
                $total = count($links);
                foreach ($links as $key => $value) {
                    $this->color("内存占用:" . Func::getMemoryUsage(), 'info');
                    $this->color("正在获取链接({$key}/{$total}) {$value['url']}.....................", 'info');
                    $html = $this->getHtml($value['url']);
                    preg_match('/(?<=watch)\?v=[^\?]+/', $html->html(), $videoId);
                    if (!empty($videoId)) {
                        $videoId = trim($videoId[0], '?v=');
                        $links[$key]['url'] = "http://ott.topertv.com:12389/play/{$videoId}?resolve=youtube&sign=";
                    }

                    $this->goSleep([1,2]);
                }

                $data['links'] = $links;
                $this->model->collect($data, 'youtube');
            } else {
                $this->model->collect($data, 'hplus');
            }
        }

        unset($data, $links, $total, $html, $videoId);
    }

}