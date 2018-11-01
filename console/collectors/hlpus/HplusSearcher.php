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

    public function collectMovie()
    {
        $dom = $this->getDom("http://hplus.com.vn/en/categories/movies");

        $tasks = [];
        $pageTotal = $dom->filter('.pagination li')->count();
        for ($page = 1; $page<=$pageTotal; $page++) {
            if ($page == 1) {
                $tasks[] = "http://hplus.com.vn/en/categories/movies";
            } else {
                $tasks[] = "http://hplus.com.vn/en/categories/movies/{$page}";
            }
        }


        if (empty($tasks)) return false;

        foreach ($tasks as $url) {
            $dom = $this->getDom($url);
            // 从第一页开始读取数据
            $dom->filter('.panel-wrapper .image-wrapper')->each(function(Crawler $wrapper) {
                $tooltips = $wrapper->filter('.tooltips')->first();
                $this->href[] = "http://hplus.com.vn/".$tooltips->attr('href');
            });
            sleep(2);
        }

        if (!empty($this->href)) {
            foreach ($this->href as $href) {
                $dom = $this->getDom($href);
                $vod['url']    = $href;
                $vod['title']  = $dom->filter('title')->first()->text();
                $vod['image']  =$dom->filter(".featureImg-inner img")->first()->attr('src');

                $dom->filter('.overview-box .overview-item')->each(function(Crawler $item) use($vod){
                    $span  = $item->filter('span');
                    $field = $span->eq(0)->text();
                    $value = $span->eq(1)->text();
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
                            $vod['vod_year'] = $value;
                            break;
                    }
                });

                $vod['info'] = trim($dom->filter('.description-detail-inner .content-inner')->first()->text());

                if (method_exists($this->model, 'collect')) {
                    $this->model->collect($vod, 'hplus');
                }

                echo "睡眠1s" . PHP_EOL;
                sleep(1);
            }
        }
    }

}