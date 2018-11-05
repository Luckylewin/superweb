<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/15
 * Time: 13:43
 */

namespace console\models;

use backend\models\PlayGroup;
use common\models\Vod;
use common\models\Vodlink;
use common\models\VodList;
use Yii;

class Movie extends Vod
{
    public $language;
    public $area;

    public function setLanguage($language = 'Vietnamese')
    {
        $this->language = $language;
    }

    public function setArea($area = 'Vietnam')
    {
        $this->area = $area;
    }

    public function collect($data, $playGroupName = 'default')
    {
        $title     = $data['title'];
        $url       = $data['url'];
        $image     = $data['image'];
        $info      = $data['info'];
        $groupName = $playGroupName;

        $title = trim($title);
        $vod = Vod::findOne(['vod_name' => $title]);

        if (empty($vod)) {
            $genre = VodList::findOne(['list_dir' => 'Movie']);
            if (empty($genre)) {
                echo "请新增Movie分类" , PHP_EOL;
                return false;
            }

            $movie = new Vod();
            $movie->vod_name = $title;
            $movie->vod_pic = "http://192.200.112.162:12389/proxy?path=".$image;
            $movie->vod_pic_bg = $movie->vod_pic;
            $movie->vod_scenario = $movie->vod_content = $info;
            $movie->vod_area = $this->area;
            $movie->vod_language = $this->language;
            $movie->vod_cid = $genre->list_id;
            $movie->vod_trysee = 0;
            $movie->vod_total = 1;


            if (isset($data['vod_type'])) $movie->vod_type         = $data['vod_type'];
            if (isset($data['vod_actor'])) $movie->vod_actor       = $data['vod_actor'];
            if (isset($data['vod_director'])) $movie->vod_director = $data['vod_director'];
            if (isset($data['vod_area'])) $movie->vod_area         = $data['vod_area'];
            if (isset($data['vod_length'])) $movie->vod_length     = $data['vod_length'];
            if (isset($data['vod_year'])) $movie->vod_year         = $data['vod_year'];

            $movie->save(false);

            // 新增一个播放分组
            $playGroup = new PlayGroup();
            $playGroup->vod_id = $movie->vod_id;
            $playGroup->group_name = $groupName;
            $playGroup->save(false);

            // 新增播放链接
            $link = new Vodlink();
            $link->url = $url;
            $link->episode = 1;
            $link->group_id = $playGroup->id;
            $link->save(false);

            echo $title . "新增" . PHP_EOL;
        } else {
            echo $title . "存在" . PHP_EOL;
        }
        return false;
    }
}