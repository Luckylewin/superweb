<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/23
 * Time: 17:22
 */

namespace console\models;

use common\models\Vod;
use backend\models\PlayGroup;
use common\models\Vodlink;
use common\models\VodList;


class Variety extends Vod
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


    public function judgeIsExist($title)
    {
        return Vod::find()->where(['vod_name' => $title])->exists();
    }

    public function collect($data, $playGroupName = 'default')
    {
        $title = $data['title'];
        $image = $data['image'];
        $info  = $data['info'];
        $groupName = $playGroupName;

        $title = trim($title);
        if (empty($title)) {
            return false;
        }

        $vod = Vod::findOne(['vod_name' => $title]);

        if (empty($vod)) {
            $genre = VodList::findOne(['list_dir' => 'Serial']);
            if (empty($genre)) {
                echo "请新增Serial分类" , PHP_EOL;
                return false;
            }

            $tvShow = new Vod();

            $tvShow->vod_name = $title;
            $tvShow->vod_pic = $image;
            $tvShow->vod_pic_bg = $image;
            $tvShow->vod_content = $info;
            $tvShow->vod_language = $this->language;
            $tvShow->vod_area = $this->area;
            $tvShow->vod_cid = $genre->list_id;
            $tvShow->vod_trysee = 0;
            $tvShow->vod_total = count($data['links']);
            $tvShow->vod_multiple = 1;
            $tvShow->vod_isend = 1;

            if (isset($data['vod_keywords'])) $tvShow->vod_keywords = $data['vod_keywords'];
            if (isset($data['vod_type'])) $tvShow->vod_type         = $data['vod_type'];
            if (isset($data['vod_actor'])) $tvShow->vod_actor       = $data['vod_actor'];
            if (isset($data['vod_director'])) $tvShow->vod_director = $data['vod_director'];
            if (isset($data['vod_area'])) $tvShow->vod_area         = $data['vod_area'];
            if (isset($data['vod_length'])) $tvShow->vod_length     = $data['vod_length'];
            if (isset($data['vod_filmtime'])) $tvShow->vod_filmtime = $data['vod_filmtime'];
            if (isset($data['vod_year'])) $tvShow->vod_year         = $data['vod_year'];

            $tvShow->save(false);

            // 新增一个播放分组
            $playGroup = new PlayGroup();
            $playGroup->vod_id = $tvShow->vod_id;
            $playGroup->group_name = $groupName;
            $playGroup->save(false);

            // 新增播放链接
            if (!empty($data['links'])) {
                foreach ($data['links'] as $_link) {
                    $link = new Vodlink();
                    $link->url = $_link['url'];
                    $link->episode = $_link['episode'];
                    $link->group_id = $playGroup->id;
                    $link->save(false);
                }
            }

            echo  " {$title}新增" . PHP_EOL;
        } else {
            echo  " {$title}存在" . PHP_EOL;
        }
        return false;
    }
}