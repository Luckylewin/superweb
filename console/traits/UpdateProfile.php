<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/10
 * Time: 10:51
 */

namespace console\traits;

use common\models\OttLink;
use common\models\Vod;
use common\models\VodList;
use common\models\Vodlink;
use backend\models\PlayGroup;
use yii\helpers\Console;

trait UpdateProfile
{
    public $language;

    public $area;


    /**
     * @param $dirName string 分类名称
     * @param $data array 数据
     * @param $playGroupName string 分组名称
     * @return bool
     */
    public function work($dirName, $data, $playGroupName)
    {
        $genre = VodList::findByDirName($dirName);

        $title = $this->getTitle($data);

        $vod = $this->getVodInGenre($genre, $title);

        if (empty($genre)) {
            Console::error("请新增Serial分类");
        }

        if (is_null($vod)) {
            $this->create($genre,$data,$playGroupName);
        } else {
            $this->updateProfile($vod, $data);
        }

        return true;
    }

    public function getTitle($data)
    {
        return $data['title']??$data['vod_name'];
    }

    public function getVodInGenre($genre, $title)
    {
        return Vod::find()->where([
            'vod_name' => $title,
            'vod_cid' => $genre->list_id
        ])->one();
    }

    public function create($genre,$data,$groupName)
    {
        if (isset($data['title'])) {
            $title     = $data['title']??"";
            $url       = $data['url']??"";
            $image     = $data['image']??"";
            $info      = $data['info']??"";
        } else {
            $title     = $data['vod_name']??"";
            $url       = $data['links'][0]['url']??"";
            $image     = $data['vod_pic']??"";
            $info      = $data['vod_content']??"";
        }

        $title = trim($title);
        if (empty($title)) {
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
        $tv->vod_total = isset($data['links']) ? count($data['links']) : 1;
        $tv->vod_multiple = 1;
        $tv->vod_isend = 1;

        $perhapsFields = [
            'vod_keywords', 'vod_type', 'vod_actor', 'vod_director',
            'vod_area', 'vod_length', 'vod_filmtime', 'vod_year',
            'vod_hits', 'vod_up', 'vod_pic_bg', 'vod_pic_slide',
            'vod_reurl', 'vod_language', 'vod_area', 'vod_origin_url',
            'vod_gold', 'vod_golder','vod_genre','is_top'
        ];

        foreach ($perhapsFields as $field) {
            if (isset($data[$field])) $tv->$field = $data[$field];
        }

        $tv['vod_total'] = $tv->vod_total;

        $tv->save(false);

        // 新增一个播放分组
        $playGroup = new PlayGroup();
        $playGroup->vod_id = $tv->vod_id;
        $playGroup->group_name = $groupName;
        $playGroup->save(false);

        if (empty($data['links']) && !empty($url)) {
            $data['links'][] = [
                'url' => $url,
                'episode' => 1,
                'title' => 'episode 1'
            ];
        }

        // 新增播放链接
        if (!empty($data['links'])) {
            foreach ($data['links'] as $_link) {
                $this->createLinks($playGroup->id, $_link);
            }
        }

        echo  " {$title}新增" . PHP_EOL;
        return true;
    }

    /**
     * @param Vod $vod
     * @param $data
     */
    public function updateProfile(Vod $vod, $data)
    {
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

        // 获取该剧集的 group_id
        $playGroup = PlayGroup::findOne(['vod_id' => $vod->vod_id]);

        // 判断剧集是否更新
        foreach ($data['links'] as $_link) {

            if (Vodlink::find()->where(['url' => $_link['url'], 'group_id' => $playGroup->id])->exists() == false) {
                 $this->createLinks($playGroup->id, $_link);
                 echo $vod->vod_name . "更新剧集" . PHP_EOL;
            }
        }

        $update && $vod->save(false);
        echo $vod->vod_name . "存在" . PHP_EOL;
    }

    private function createLinks($playGroupId, $_link)
    {
        $link = new Vodlink();
        $link->url      = $_link['url'];
        $link->episode  = $_link['episode'];
        $link->group_id = $playGroupId;
        if (isset($_link['during'])) $link->during = $_link['during'];
        if (isset($_link['title']))  $link->title  = $_link['title'];
        if (isset($_link['pic']))    $link->pic    = $_link['pic'];

        $link->save(false);
    }

    protected function getImagePath($playGroupName, $path)
    {
        if (strtolower($playGroupName) == 'youtube') {
            return "http://192.200.112.162:12389/proxy?path=" . $path;
        }

        return $path;
    }

    public function setLanguage($language = 'Vietnamese')
    {
        $this->language = $language;
    }

    public function setArea($area = 'Vietnam')
    {
        $this->area = $area;
    }
}