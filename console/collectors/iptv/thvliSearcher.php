<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/7
 * Time: 16:01
 */

namespace console\collectors\iptv;

use common\models\Type;
use console\collectors\common;
use yii\base\Model;

class thvliSearcher extends common
{
    public $model;
    public $resoure = 'thvli';

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
        // 'https://api.thvli.vn/backend/cm/page/phim-nuoc-ngoai/?platform=web',

        $goalLinks = [
            'https://api.thvli.vn/backend/cm/ribbon/fa5c83fa-42ca-4d27-902d-e57db537cde1/?page=',
            // 动作
            'https://api.thvli.vn/backend/cm/ribbon/b0dea637-6e50-4985-b2e2-c20f16bb23c4/?page=',
            // 感情
            'https://api.thvli.vn/backend/cm/ribbon/b7b746ed-a806-4829-829a-334e8d1e0025/?page=',
            // 喜剧-恐怖-古装
            'https://api.thvli.vn/backend/cm/ribbon/9e7181ab-6ac0-4f4f-b88b-bc32f0774feb/?page=',
            // 迷你短剧情
            'https://api.thvli.vn/backend/cm/ribbon/b3d13150-79d1-46c2-956d-d15a3ca9c049/?page='
        ];

        $this->doCollect($goalLinks);
    }

    public function collectVariety()
    {

        // 页码计算
        $goalLinks = [
            'https://api.thvli.vn/backend/cm/ribbon/d058fbe6-8e2d-4d40-8776-1da43c94d5c0/?page=',
            // 喜剧
            'https://api.thvli.vn/backend/cm/ribbon/f422bb58-002d-4a54-83b6-984f1e0c3e26/?page=',
            // 音乐综艺
            'https://api.thvli.vn/backend/cm/ribbon/e1ac64c7-bdd6-4408-b8e5-bae1d0e18137/?page='
        ];

        $this->doCollect($goalLinks);
    }

    public function collectCartoon()
    {
        $goalLinks = [
            'https://api.thvli.vn/backend/cm/ribbon/2de2543d-f5f3-469e-919b-f09036f50165/?page='
        ];

        $this->doCollect($goalLinks);
    }

    protected function doCollect($goalLinks)
    {
        $tasks = $this->getFinalTasks($goalLinks);
        $links = [];
        foreach ($tasks as $link) {
            $links = array_merge($links, $this->getHref($link));
        }

        if (!empty($links)) {
            $total = count($links);
            foreach ($links as $key => $link) {
                $this->color("正在爬取({$key}/{$total}):{$link['link']}>>>>>>>>>>>>>>>>>>>>");
                try {
                    $data  = $this->getProfile($link['link']);

                    $links = $this->getEpisode($data['episode_id'], $data['title']);

                    $data['vod_type']     = $link['type'];
                    $data['vod_area']     = $link['area'];
                    $data['vod_language'] = $link['language'];
                    $data['links']        = $links;

                } catch (\Exception $e) {
                    $this->debug($e);
                    continue;
                }

                $this->createVod($data, 'thvil');
                $this->goSleep([3,5]);
            }
        }
    }

    protected function getHref($link)
    {
        $json  = $this->getJson($link);
        $links = [];

        $idToMap = [
            'fa5c83fa-42ca-4d27-902d-e57db537cde1' => [
                'language' => Type::LANGUAGE_CHINESE,
                'area'     => Type::AREA_CHINA,
                'detail'   => 'https://www.thvli.vn/backend/cm/detail/',
                'type'     => 'Costume,History,Romance'
            ],
            '5d0ec3dc-bf74-4ce2-ab12-c327d1d707fa' => [
                'language' => Type::LANGUAGE_HINDI,
                'area'     => Type::AREA_INDIA,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'History,Romance'
            ],
            //https://www.thvli.vn/ribbon/32ca4139-b0ee-48ce-b331-eee46bbf4575
            '32ca4139-b0ee-48ce-b331-eee46bbf4575' => [
                'language' => Type::LANGUAGE_THAT,
                'area'     => Type::AREA_THAILAND,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Romance'
            ],
            //https://www.thvli.vn/ribbon/2de2543d-f5f3-469e-919b-f09036f50165
            '2de2543d-f5f3-469e-919b-f09036f50165' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'kids'
            ],

            //https://www.thvli.vn/ribbon/b3d13150-79d1-46c2-956d-d15a3ca9c049/page/24
            'b3d13150-79d1-46c2-956d-d15a3ca9c049' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Other'
            ],
            // https://www.thvli.vn/ribbon/b7b746ed-a806-4829-829a-334e8d1e0025
            'b7b746ed-a806-4829-829a-334e8d1e0025' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Romance'
            ],
            // https://www.thvli.vn/ribbon/b0dea637-6e50-4985-b2e2-c20f16bb23c4/page/1
            'b0dea637-6e50-4985-b2e2-c20f16bb23c4' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Action'
            ],
            // https://www.thvli.vn/ribbon/9e7181ab-6ac0-4f4f-b88b-bc32f0774feb
            '9e7181ab-6ac0-4f4f-b88b-bc32f0774feb' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Comedy,Costume,Horror'
            ],
            // https://www.thvli.vn/ribbon/d058fbe6-8e2d-4d40-8776-1da43c94d5c0/page/1
            'd058fbe6-8e2d-4d40-8776-1da43c94d5c0' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Game Show'
            ],
            //https://www.thvli.vn/ribbon/f422bb58-002d-4a54-83b6-984f1e0c3e26
            'f422bb58-002d-4a54-83b6-984f1e0c3e26' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Comedy'
            ],
            // https://www.thvli.vn/ribbon/7ae8d3ff-78d5-4295-b413-448786d6bc68
            '7ae8d3ff-78d5-4295-b413-448786d6bc68' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Bolero'
            ],
            // https://www.thvli.vn/ribbon/e1ac64c7-bdd6-4408-b8e5-bae1d0e18137
            'e1ac64c7-bdd6-4408-b8e5-bae1d0e18137' => [
                'language' => Type::LANGUAGE_VIETNAMESE,
                'area'     => Type::AREA_VIETNAM,
                'detail'   => 'https://api.thvli.vn/backend/cm/detail/',
                'type'     => 'Music'
            ],
        ];

        $item = $json;

        if (is_object($json) && !empty($json) && isset($json->banners)) {
            foreach ($json as $items) {
                foreach ($items as $item) {
                    if (in_array($item->id, array_keys($idToMap))) {
                        if (!empty($item->items)) {
                            foreach ($item->items as $itemObj) {

                                $links[] = [
                                    'link'     => $idToMap[$item->id]['detail']. $itemObj->slug . '/',
                                    'area'     => $idToMap[$item->id]['area'],
                                    'language' => $idToMap[$item->id]['language'],
                                    'type'     => $idToMap[$item->id]['type'],
                                ];

                            }
                        }
                    }

                }
            }
        } elseif (in_array($item->id, array_keys($idToMap))) {
           if (!empty($item->items)) {
               foreach ($item->items as $itemObj) {

                   $links[] = [
                       'link'     => $idToMap[$item->id]['detail']. $itemObj->slug . '/',
                       'area'     => $idToMap[$item->id]['area'],
                       'language' => $idToMap[$item->id]['language'],
                       'type'     => $idToMap[$item->id]['type'],
                   ];

               }
           }
       }

       return $links;
    }

    protected function setProfile($detail, $link)
    {
        $profile = [
            'id'            => isset($detail->id) ? $detail->id : '',
            'title'         => $detail->title,
            'image'         => $detail->images->thumbnail,
            'info'          => $detail->long_description,
            'vod_year'      => substr($detail->release_date, 0 , 4),
            'vod_filmtime'  => $detail->publish_date,
            'vod_pic_bg'    => $detail->images->poster,
            'vod_pic_slide' => $detail->images->banner,
            'vod_hits'      => $detail->views,
            'vod_up'        => $detail->favorites,
            'episode_id'    => !empty($detail->seasons) ? $detail->seasons[0]->id  : false,
            'vod_director'  => !empty($detail->people)  ? $detail->people[0]->name : '',
            'vod_actor'     => !empty($detail->people) && isset($detail->people[1])  ? $detail->people[1]->name : '',
            'vod_reurl'     => 'thvli',
            'vod_origin_url' => $link
        ];

        return $profile;
    }

    protected function getProfile($link)
    {
        $detail  = $this->getJson($link);
        $profile = $this->setProfile($detail, $link);

        $this->goSleep(2);

        return $profile;
    }

    protected function getEpisode($episode_id, $title)
    {
        $episodes = [];
        if ($episode_id) {
            $url  = "https://api.thvli.vn/backend/cm/season_by_id/{$episode_id}/";
            $json = $this->getJson($url);

            if (!empty($json) && !empty($json->episodes)) {
                foreach ($json->episodes as $item) {
                    $_title = str_replace($title, '', $item->title);
                    $_title = trim($_title);
                    $_title = trim($_title, '-');
                    $_title = trim($_title);

                    if ($pos   = strpos($_title, 'Phần')) {
                        $_title = substr($_title, $pos);
                        $_title = trim($_title);
                        $_title = trim($_title, '-');
                        $_title = trim($_title);
                    }

                    $episodes[] = [
                        'title'   => $_title,
                        'episode' => $item->episode,
                        'pic'     => $item->images->thumbnail,
                        'url'     => "https://api.thvli.vn/backend/cm/detail/{$item->id}/",
                    ];

                }
            }

            $this->goSleep([1,2]);
        }

        return $episodes;
    }

    protected function getFinalTasks($goalLinks)
    {
        $tasks = [];
        foreach ($goalLinks as $link) {

            $json = $this->getJson($link . '0');
            if ($json) {
                $total = $json->metadata->total;
                $limit = $json->metadata->limit;
                $pageTotal = ceil($total/$limit);
                for ($page=0; $page<$pageTotal; $page++) {
                    $tasks[] = $link . $page;
                }
            }
            $this->goSleep(1);
        }

        return $tasks;
    }

    public function collectSpecialCartoon()
    {
        $data = [];

        $view = $up = 0;

        for ($page = 43; $page>=0; $page--) {
            $link = "https://api.thvli.vn/backend/cm/ribbon/513eb74d-2731-4f27-a493-291c698c68fe/?page={$page}";
            $json = $this->getJson($link);
            if (!empty($json)) {
                foreach ($json->items as $key => $item) {
                     if ($key == 0 && $page == 0) {
                         $data = array_merge($data, $this->setProfile($item, $link));
                         $data['vod_type'] = 'kids';
                     }
                     preg_match('/\d+/', $item->title, $episode);
                     $episode = empty($episode) ? 1 : $episode[0];

                     $data['links'][$episode - 1] = [
                         'title'   => $item->title,
                         'episode' => $episode,
                         'pic'     => $item->images->thumbnail,
                         'url'     => "https://api.thvli.vn/backend/cm/detail/{$item->id}/",
                     ];
                     $view += $item->views;
                     $up   += $item->favorites;

                }
            }

            //    print_r($data);
            $this->goSleep([1,3]);
        }

        $data['vod_hits'] = $view;
        $data['vod_up']   = $up;

        $this->createVod($data);

    }



}