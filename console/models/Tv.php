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


class Tv extends Vod
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

            $tv = new Vod();

            $tv->vod_name = $title;
            $tv->vod_pic = $image;

            $tv->vod_content = $info;
            $tv->vod_language = $this->language;
            $tv->vod_area = $this->area;
            $tv->vod_cid = $genre->list_id;
            $tv->vod_trysee = 0;
            $tv->vod_total = count($data['links']);
            $tv->vod_multiple = 1;
            $tv->vod_isend = 1;

            if (isset($data['vod_keywords'])) $tv->vod_keywords     = $data['vod_keywords'];
            if (isset($data['vod_type'])) $tv->vod_type             = $data['vod_type'];
            if (isset($data['vod_actor'])) $tv->vod_actor           = $data['vod_actor'];
            if (isset($data['vod_director'])) $tv->vod_director     = $data['vod_director'];
            if (isset($data['vod_area'])) $tv->vod_area             = $data['vod_area'];
            if (isset($data['vod_length'])) $tv->vod_length         = $data['vod_length'];
            if (isset($data['vod_filmtime'])) $tv->vod_filmtime     = $data['vod_filmtime'];
            if (isset($data['vod_year'])) $tv->vod_year             = $data['vod_year'];
            if (isset($data['vod_hits'])) $tv->vod_hits             = $data['vod_hits'];
            if (isset($data['vod_up'])) $tv->vod_up                 = $data['vod_up'];
            if (isset($data['vod_pic_bg'])) $tv->vod_pic_bg         = $data['vod_pic_bg'];
            if (isset($data['vod_pic_slide'])) $tv->vod_pic_slide   = $data['vod_pic_slide'];
            if (isset($data['vod_reurl'])) $tv->vod_reurl           = $data['vod_reurl'];
            if (isset($data['vod_language'])) $tv->vod_language     = $data['vod_language'];
            if (isset($data['vod_area'])) $tv->vod_area             = $data['vod_area'];
            if (isset($data['vod_origin_url'])) $tv->vod_origin_url  = $data['vod_origin_url'];

            $tv['vod_total'] = count($data['links']);

            $tv->save(false);

            // 新增一个播放分组
            $playGroup = new PlayGroup();
            $playGroup->vod_id = $tv->vod_id;
            $playGroup->group_name = $groupName;
            $playGroup->save(false);


            // 新增播放链接
            if (!empty($data['links'])) {
                foreach ($data['links'] as $_link) {
                    $link = new Vodlink();
                    $link->url      = $_link['url'];
                    $link->episode  = $_link['episode'];
                    $link->group_id = $playGroup->id;
                    if (isset($_link['during'])) $link->during = $_link['during'];
                    if (isset($_link['title']))  $link->title  = $_link['title'];
                    if (isset($_link['pic']))    $link->pic    = $_link['pic'];

                    $link->save(false);
                }
            }

            echo  " {$title}新增" . PHP_EOL;
        } else {
            $update = false;

            // 判断地区是否有了数据
            if (isset($data['vod_area']) && empty($vod->vod_area)) {
                $vod->vod_area = $data['vod_area'];
                $update = true;
            }

            // 判断地区是否有了数据
            if (isset($data['vod_language']) && empty($vod->vod_language)) {
                $vod->vod_language = $data['vod_language'];
                $update = true;
            }

            // 判断类型是否一致 取出vod_type 字段
            if (isset($data['vod_type']) && !empty($data['vod_type']) && $data['vod_type'] != 'Other') {
                $old  = explode(',', $vod->vod_type);
                $new  = explode(',', $vod['vod_type']);
                $diff = array_diff($new, $old);
                if (!empty($diff)) {
                    $new = array_merge($old, $new);
                    $vod->vod_type = implode(',', $new);
                    $update = true;

                }
            }

            if (empty($vod->vod_type)) {
                $vod->vod_type = 'Other';
                $update = true;
            }

            $update && $vod->save(false);
            echo  " {$title}存在" . PHP_EOL;
        }
        return false;
    }
}