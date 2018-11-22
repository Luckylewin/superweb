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
            $genre = VodList::findOne(['list_dir' => 'Variety']);
            if (empty($genre)) {
                echo "请新增Variety分类" , PHP_EOL;
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

            $perhapsFields = [
                'vod_keywords', 'vod_type', 'vod_actor', 'vod_director',
                'vod_area', 'vod_length', 'vod_filmtime', 'vod_year',
                'vod_hits', 'vod_up', 'vod_pic_bg', 'vod_pic_slide',
                'vod_reurl', 'vod_language', 'vod_area', 'vod_origin_url',
                'vod_gold', 'vod_golder'
            ];

            foreach ($perhapsFields as $field) {
                if (isset($data[$field])) $tvShow->$field = $data[$field];
            }

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
                    $link->url     = $_link['url'];
                    $link->episode = $_link['episode'];
                    $link->group_id = $playGroup->id;

                    if (isset($_link['title']))  $link->title   = $_link['title'];
                    if (isset($_link['pic']))    $link->pic     = $_link['pic'];
                    if (isset($_link['during'])) $link->during  = $_link['during'];

                    $link->save(false);
                }
            }

            echo  " {$title}新增" . PHP_EOL;
        } else {
            $update = false;

            if (isset($data['vod_gold']) && empty($vod->vod_gold)) {
                $vod->vod_gold   = $data['vod_gold'];
                $vod->vod_golder = $data['vod_golder'];
                $update = true;
            }


            // 判断地区是否有了数据
            if (isset($data['vod_area']) && (empty($vod->vod_area) || $data['vod_area'] != $vod->vod_area)) {
                $vod->vod_area = $data['vod_area'];
                $update = true;
            }

            // 判断语言是否有了数据
            if (isset($data['vod_language']) && (empty($vod->vod_language) || $data['vod_language'] != $vod->vod_language )) {
                $vod->vod_language = $data['vod_language'];
                $update = true;
            }

            // 判断类型是否一致 取出vod_type 字段
            if (isset($data['vod_type']) && !empty($data['vod_type'])) {
                $old  = explode(',', $vod->vod_type);
                $new  = explode(',', $vod['vod_type']);
                $diff = array_diff($new, $old);
                if (!empty($diff)) {
                    $new = array_merge($old, $new);
                    $vod->vod_type = implode(',', $new);
                    $update = true;
                }
            }

            $update && $vod->save(false);
            echo  " {$title}存在" . PHP_EOL;
        }
        return false;
    }
}