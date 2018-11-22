<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/15
 * Time: 13:42
 */

namespace console\collectors\iptv;


use common\components\Func;
use common\models\Type;
use console\collectors\common;
use Symfony\Component\DomCrawler\Crawler;
use yii\base\Model;

class zingSearcher extends common
{
    public $model;
    public $resource = 'zingtv';
    public $site = "https://tv.zing.vn/";

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function collectTv()
    {
        $goalLinks = [
            [
                'type' => 'American drama',
                'url'  => 'https://tv.zing.vn/the-loai/Phim-Au-My/IWZ9ZI09.html',
                'area' => 'America',
                'language' => 'English'
            ],
            [
                'type' => 'Hover',
                'url'  => 'https://tv.zing.vn/the-loai/Kinh-Di-Sieu-Nhien/IWZ9ZI0W.html'
            ],
            [
                'type' => 'Costume',
                'url'  => 'https://tv.zing.vn/the-loai/Lich-Su-Co-Trang/IWZ9ZI06.html'
            ],
            [
                'type' => 'Romance',
                'url'  => 'https://tv.zing.vn/the-loai/Tam-Ly-Lang-Man/IWZ9ZI0I.html'
            ],
            [
                'type' => 'Crime',
                'url'  => 'https://tv.zing.vn/the-loai/Hinh-Su-Toi-Pham/IWZ9ZI0O.html'
            ],
            [
                'type' => 'Action',
                'url'  => 'https://tv.zing.vn/the-loai/Hanh-Dong-Phieu-Luu/IWZ9Z0FF.html'
            ],
            [
                'type' => 'Science,Adventure',
                'url'  => 'https://tv.zing.vn/the-loai/Khoa-Hoc-Vien-Tuong/IWZ9ZIWF.html'
            ],
            [
                'type' => 'Other',
                'area' => 'Korea',
                'url'  => 'https://tv.zing.vn/the-loai/Phim-Han-Quoc/IWZ9ZI08.html'
            ],
            [
                'type' => 'Other',
                'area' => 'Thailand',
                'url'  => 'https://tv.zing.vn/the-loai/Phim-Thai-Lan/IWZ9ZIUE.html'
            ],
            [
                'type' => 'Other',
                'area' => 'China',
                'url'  => 'https://tv.zing.vn/the-loai/Phim-Hoa-Ngu/IWZ9ZI0A.html'
            ],
            [
                'type' => 'Other',
                'area' => 'Taiwan, China',
                'url'  => 'https://tv.zing.vn/the-loai/Phim-Dai-Loan/IWZ9ZIWC.html'
            ],
            [
                'type' => 'Other',
                'area' => 'Japan',
                'url'  => 'https://tv.zing.vn/the-loai/Phim-Nhat-Ban/IWZ9ZIW6.html'
            ],
            [
                'type' => 'Other',
                'area' => 'Vietnam',
                'url'  => 'https://tv.zing.vn/the-loai/Phim-Viet-Nam/IWZ9ZI07.html'
            ],
        ];

        $this->doCollect($goalLinks);
    }


    public function collectVariety()
    {
        $goalLinks = [

            [
                'type' => 'Stage',
                'url'  => 'https://tv.zing.vn/the-loai/San-Khau-Liveshow-Hai/IWZ9ZIIW.html',
                'area' => 'Vietnam'
            ],
            [
                'type' => 'Music',
                'url'  => 'https://tv.zing.vn/the-loai/Show-Am-Nhac/IWZ9ZI6U.html'
            ],
            [
                'type' => 'Comedy',
                'url'  => 'https://tv.zing.vn/the-loai/Tieu-Pham-Hai/IWZ9ZIII.html',
                'area' => 'Vietnam',
                'language' => 'Vietnamese'
            ],
            [
                'type' => 'Chinese Variety',
                'url'  => 'https://tv.zing.vn/the-loai/Nhac-Hoa-Ngu/IWZ9ZI6W.html',
                'area' => 'Chinese'
            ],
            [
                'type' => 'Reality Show',
                'url'  => 'https://tv.zing.vn/the-loai/Show-Thuc-Te/IWZ9ZIIU.html'
            ],
            [
                'type' => 'Show Fun',
                'url'  => 'https://tv.zing.vn/the-loai/Show-Hai-Huoc/IWZ9ZI6O.html'
            ],
            [
                'type' => 'Talk show',
                'url'  => 'https://tv.zing.vn/the-loai/Talk-Show/IWZ9ZII6.html'
            ],
            [
                'type' => 'Korean Variety',
                'url'  => 'https://tv.zing.vn/the-loai/Show-Han-Quoc/IWZ9ZII8.html',
                'area' => 'Korea'
            ],
            [
                'type' => 'America Variety',
                'url'  => 'https://tv.zing.vn/the-loai/Show-Au-My/IWZ9ZII9.html',
                'area' => 'America',
                'language' => 'English'
            ],
            [
                'type' => 'Local Variety',
                'url'  => 'https://tv.zing.vn/the-loai/Show-Viet-Nam/IWZ9ZII7.html',
                'area' => 'Vietnam',
                'language' => 'Vietnamese'
            ],
            [
                'type' => 'Game Show',
                'url'  => 'https://tv.zing.vn/the-loai/Show-Viet-Nam/IWZ9ZII7.html'
            ],
            [
                'type' => 'Music',
                'url'  => 'https://tv.zing.vn/the-loai/Nhac-Viet-Nam/IWZ9ZIW0.html',
            ],
            [
                'type' => 'Music',
                'url'  => 'https://tv.zing.vn/the-loai/Nhac-Han-Quoc/IWZ9ZIWW.html',
            ],
            [
                'type' => 'Live show',
                'url'  => 'https://tv.zing.vn/the-loai/Liveshow/IWZ9ZIIF.html'
            ]
        ];

        $this->doCollect($goalLinks);
    }

    public function collectCartoon()
    {
        $goalLinks = [
            [
                'type' => 'Psychological',
                'url'  => 'https://tv.zing.vn/the-loai/Tam-Ly/IWZ9ZI9C.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Robot',
                'url'  => 'https://tv.zing.vn/the-loai/Mecha-Robot/IWZ9ZI9B.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Costume',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-Hinh-Trung-Quoc/IWZ9798Z.html',
                'area' => 'China'
            ],
            [
                'type' => 'Lolita',
                'url'  => 'https://tv.zing.vn/the-loai/Seinen-Josei/IWZ9ZI9A.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Shoujo',
                'url'  => 'https://tv.zing.vn/the-loai/Shoujo/IWZ9ZI99.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Shounen',
                'url'  => 'https://tv.zing.vn/the-loai/Hanh-Dong-Phieu-Luu/IWZ9Z0FF.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Harem',
                'url'  => 'https://tv.zing.vn/the-loai/Harem/IWZ9ZI96.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Game',
                'url'  => 'https://tv.zing.vn/the-loai/Game/IWZ9ZI9O.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Dark',
                'url'  => 'https://tv.zing.vn/the-loai/Kinh-Di/IWZ9ZI9I.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Martial Art',
                'url'  => 'https://tv.zing.vn/the-loai/Vo-Thuat-Kiem-Hiep/IWZ9ZI90.html',
                'area' => 'China'
            ],
            [
                'type' => 'Sport',
                'url'  => 'https://tv.zing.vn/the-loai/The-Thao/IWZ9ZI8F.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'History',
                'url'  => 'https://tv.zing.vn/the-loai/Lich-Su/IWZ9ZI8E.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Science Fiction',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-Hinh-Khoa-Hoc-Vien-Tuong/IWZ9ZI0F.html',
                'area' => 'China'
            ],
            [
                'type' => 'kids',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-hinh/IWZ9ZI6C.html',
                'area' => 'America',
                'language' => 'English'
            ],
            [
                'type' => 'Mysterious',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-Hinh-Anime-Sieu-Nhien-Huyen-Bi/IWZ9ZI0E.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Life',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-Hinh-Doi-Thuong/IWZ9ZI8D.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Fantasy',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-Hinh-Anime-Phep-thuat-Fantasy/IWZ9ZI8C.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Music',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-Hinh-Am-Nhac/IWZ9ZI8B.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Funny',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-Hinh-Hai-Huoc/IWZ9ZI0C.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Detective',
                'url'  => 'https://tv.zing.vn/the-loai/Hoat-Hinh-Trinh-Tham/IWZ9ZI8A.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Baby',
                'url'  => 'https://tv.zing.vn/the-loai/Be-2-tuoi/IWZ9ZI67.html',
                'area' => 'America',
                'language' => 'Japanese'
            ],
            [
                'type' => 'Kids',
                'url'  => 'https://tv.zing.vn/the-loai/Be-2-4-tuoi/IWZ9ZIW9.html',
                'area' => 'America',
                'language' => 'English'
            ],
            [
                'type' => 'Child',
                'url'  => 'https://tv.zing.vn/the-loai/Be-5-7-tuoi/IWZ9ZIWB.html',
                'area' => 'America',
                'language' => 'English'
            ],
            [
                'type' => 'Child',
                'url'  => 'https://tv.zing.vn/the-loai/Be-8-12-tuoi/IWZ9ZIWA.html',
                'area' => 'Japan',
                'language' => 'Japanese'
            ]
        ];

        $this->doCollect($goalLinks);
    }

    private function getEpisode(Crawler $dom)
    {
        if ($dom->filter('.see-all')->count() == false) {
            $dom->filter('.flex-des')->each(function(Crawler $flex) use(&$episodes) {
                $href    = $flex->filter('a')->count() ? $this->site.$flex->filter('a')->first()->attr('href') : '';
                $episode = $flex->filter('.description .title')->count() ? Func::pregSieze('/\d+/', $flex->filter('.description .title')->first()->text()) : '';
                $title   = $flex->filter('.description .title')->count() ? $flex->filter('.description .title')->first()->text() : '';
                $image   = $flex->filter('._slideimg')->count() ? $flex->filter('._slideimg')->first()->attr('src') : '';
                $during  = $flex->filter('.info-video')->text();
                if ($episode) {
                    $episodes[] = [
                        'title'   => trim($title),
                        'episode' => $episode,
                        'pic'     => $image,
                        'url'     => $href,
                        'during'  => $during
                    ];
                }
            });

            if (!empty($episodes)) {
                array_multisort(array_column($episodes, 'episode'),SORT_ASC, $episodes);
                return $episodes;
            }

            return false;
        }

        if ($dom->filter('.see-all a')->count() == 0) {
            return false;
        }

        $episodeLink = $this->site . $dom->filter('.see-all a')->first()->attr('href');
        $dom         = $this->getDom($episodeLink);

        // 获取页码
        $episodePageLinks = $this->getPageLinks($dom, $episodeLink);
        $episodes         = [];

        foreach ($episodePageLinks as $key => $link) {
            $this->getDomIsCached() == false && $this->color("获取分集".($key+1).'/'.(count($episodePageLinks)));
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

            $this->goSleep([2,3], '分页');


        }

        return $episodes;
    }

    private function getProfile(Crawler $dom, $link, $type)
    {
        if ($dom->filter('.box-description strong')->count() == false) {
            return false;
        }
        $profile['title']           = $dom->filter('.box-description strong')->first()->text();
        $profile['image']           = $dom->filter('.outside-des img')->first()->attr('src');
        $profile['info']            = $dom->filter('#_description')->text();
        $profile['vod_pic_slide']   = $dom->filter('.slide .fluid-img img')->count() ? $dom->filter('.slide .fluid-img img')->first()->attr('src') : '';
        $profile['vod_total']       = $dom->filter('.see-all')->count() ? Func::pregSieze('/\d+/', $dom->filter('.see-all')->first()->text()) : '1';
        $profile['vod_gold']        = Func::pregSieze('/(?<="ratingValue": ")[^"]+/', $dom->html());
        $profile['vod_golder']      = Func::pregSieze('/(?<="ratingCount": ")[^"]+/', $dom->html());
        $profile['vod_reurl']       = $this->resource;
        $profile['vod_origin_url']  = $link;

        //

        $patterns = [
            'Việt Nam' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM
            ],
            'Hàn Quốc' => [
                'language' => Type::LANGUAGE_KOREAN,
                'area'     => Type::AREA_KOREA
            ],
            'Hoa Ngữ' => [
                'language' => Type::LANGUAGE_CHINESE,
                'area'     => Type::AREA_CHINA
            ],
            'Thái Lan' => [
                'language' => Type::LANGUAGE_THAT,
                'area'     => Type::AREA_THAILAND
            ],
            'Nhật Bản' => [
                'language' => Type::LANGUAGE_JAPANESE,
                'area'     => Type::AREA_JAPAN
            ],
            'Âu Mỹ'    => [
                'language' => Type::LANGUAGE_ENGLISH,
                'area'     => Type::AREA_AMERICA
            ]

        ];

        if ($dom->filter('.box-description .tag')->count()) {
            $tag = $dom->filter('.box-description .tag')->text();
            foreach ($patterns as $pattern => $patternData) {
               if (preg_match("/{$pattern}/", $tag)) {
                    $profile['vod_area']     = $patternData['area'];
                    $profile['vod_language'] = $patternData['language'];
                    break;
               }
            }
        }

        if ($type) $profile['vod_type'] = $type;

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
            $type  = isset($goalLink['type']) ? $goalLink['type'] : '';
            $area  = isset($goalLink['area']) ? $goalLink['area'] : '';
            $lang  = isset($goalLink['language']) ? $goalLink['language'] : '';

            $links = $this->getFinalTasks($goalLink['url']);

            $total = count($links);
            foreach ($links as $key => $link) {
                $this->color("正在爬取{$type}: ({$key}/{$total}):{$link} >>>>>>>>>>>>>>>>>>>>");
                $this->collectOne($link, $type, $area, $lang);
            }
        }
    }

    private function collectOne($link, $type, $area, $lang)
    {
        $dom      = $this->getDom($link);
        $data     = $this->getProfile($dom, $link, $type);
        $episodes = $this->getEpisode($dom);

        if ($area && empty($data['vod_area'])) $data['vod_area'] = $area;
        if ($lang && empty($data['vod_area'])) $data['vod_language'] = $lang;
        if ($type) $data['vod_type'] = $type;

        if (!empty($episodes)) {
            $data['links']        = $episodes;
            $this->createVod($data);
            $this->goSleep([3,6]);
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