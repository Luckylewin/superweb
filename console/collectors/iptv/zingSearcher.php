<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/15
 * Time: 13:42
 */

namespace console\collectors\iptv;


use common\components\Func;
use console\collectors\common;
use Symfony\Component\DomCrawler\Crawler;
use yii\base\Model;

class zingSearcher extends common
{
    public $model;
    public $resource = 'zing';
    public $site = "https://tv.zing.vn/";

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function collectAll()
    {
        $this->collectTv();
        $this->collectVariety();
        $this->collectCartoon();
    }

    public function collectTv()
    {
        $goalLinks = [
            [
                'type' => 'Action',
                'url'  => 'https://tv.zing.vn/the-loai/Hanh-Dong-Phieu-Luu/IWZ9Z0FF.html'
            ]
        ];

        $this->doCollect($goalLinks);
    }


    public function collectVariety()
    {

    }

    public function collectCartoon()
    {

    }

    private function getEpisode(Crawler $dom)
    {
        $episodeLink = $this->site . $dom->filter('.see-all a')->first()->attr('href');
        $dom         = $this->getDom($episodeLink);

        // 获取页码
        $episodePageLinks = $this->getPageLinks($dom, $episodeLink);
        $episodes         = [];

        foreach ($episodePageLinks as $key => $link) {
            $this->color("获取分集".($key+1).'/'.(count($episodePageLinks)).":{$link} >>>>>>>>>>>>>>");
            $dom  = $this->getDom($link);
            $dom->filter('.subtray .item')->each(function(Crawler $item) use (&$episodes) {
                  $href    = $this->site . $item->filter('.thumb')->first()->attr('href');
                  $image   = $item->filter('.thumb img')->first()->attr('src');
                  $title   = $item->filter('.box-description a')->first()->text();
                  $episode = Func::pregSieze('/\d+/', $title);
                  $during  = $item->filter('.info-detail span')->last()->text();

                  if ($href && $episode) {
                      $episodes[] = [
                          'title'   => trim($title),
                          'episode' => $episode,
                          'pic'     => $image,
                          'url'     => $href,
                          'during'  => $during
                      ];
                  }
            });

            $this->goSleep(1, '分页');


        }

        return $episodes;
    }

    private function getProfile(Crawler $dom, $link, $type)
    {
        $profile['title']           = $dom->filter('.box-description strong')->first()->text();
        $profile['vod_type']        = $type;
        $profile['image']           = $dom->filter('.outside-des img')->first()->attr('src');
        $profile['info']            = $dom->filter('#_description')->text();
        $profile['vod_pic_slide']   = $dom->filter('.slide .fluid-img img')->count() ? $dom->filter('.slide .fluid-img img')->first()->attr('src') : '';
        $profile['vod_total']       = Func::pregSieze('/\d+/', $dom->filter('.see-all')->first()->text());
        $profile[ 'vod_reurl']      = $this->resource;
        $profile[ 'vod_origin_url'] = $link;

        if ($dom->filter('.box-statistics li')->count()) {
            $dom->filter('.box-statistics li')->each(function (Crawler $li) use(&$profile) {
                $field = $li->filter('strong')->text();
                if ($field == 'Quốc gia: ') {
                    $profile['vod_area'] = trim(str_replace('Quốc gia: ','', $li->text()));
                }
            });
        }

        if ( $dom->filter('._artistdata')->count()) {
            $profileID  = $dom->filter('._artistdata')->attr('id');
            $artistsUrl = "https://tv.zing.vn/xhr/artist/get-artist?type=profile&id={$profileID}";
            $artists    = $this->getJson($artistsUrl, $link);
            $artistsDom = $this->getCrawler();
            if (isset($artists->html)) {
                $artistsDom->addHtmlContent($artists->html);
                if ($artistsDom->filter('#_page_artist')->count()) {
                    $artists = '';
                    $artistsDom->filter('#_page_artist .item')->each(function(Crawler $node) use (&$artists){
                        $artists .= trim($node->text()).',';
                    });
                    if (!empty($artists)) $profile['vod_actor'] = trim($artists, ',');
                }

            }
        }

        return $profile;
    }

    public function doCollect($goalLinks)
    {
        foreach ($goalLinks as $goalLink) {
            $type  = $goalLink['type'];
            $links = $this->getFinalTasks($goalLink['url']);

            $total = count($links);
            foreach ($links as $key => $link) {
                $this->color("正在爬取({$key}/{$total}):{$link} >>>>>>>>>>>>>>>>>>>>");
                $dom      = $this->getDom($link);
                $data     = $this->getProfile($dom, $link, $type);
                $episodes = $this->getEpisode($dom);

                if (isset($link['area'])) $data['vod_area'] = $link['area'];
                if (isset($link['vod_language'])) $data['vod_language'] = $link['vod_language'];
                $data['links']        = $episodes;

                $this->createVod($data);
                $this->goSleep([3,5]);
            }
        }
    }


    private function getPageLinks(Crawler $dom, $link)
    {
        $pageLinks = [];
        $maxPage = 1;
        $page    = $dom->filter('.pagination li');
        if ($page->count()) {
            $last = $page->last();
            $maxPage = Func::pregSieze('/(?<=page\=)\d+/', $last->html());
        }

        // 遍历页码
        for ($page=1; $page<=$maxPage; $page++) {
            $url = $link;
            if ($page > 1) {
                $url = $url.'?&page='.$page;
            }

            $pageLinks[] = $url;
        }

        return $pageLinks;
    }

    private function getFinalTasks($link)
    {
        $dom       = $this->getDom($link);
        $pageLinks = $this->getPageLinks($dom, $link);
        $itemsLink = [];

        if (!empty($pageLinks)) {
            foreach ($pageLinks as $link) {
                $dom  = $this->getDom($link);
                $dom->filter('.subtray .item .thumb')->each(function(Crawler $thumb) use(&$itemsLink) {
                    $itemsLink[] = $this->site . $thumb->attr('href');
                });
                $this->goSleep(2);
            }
        }

        return $itemsLink;
    }
}