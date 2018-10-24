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

            $movie = new Vod();

            $movie->vod_name = $title;
            $movie->vod_pic = $image;
            $movie->vod_pic_bg = $image;
            $movie->vod_content = $info;
            $movie->vod_cid = $genre->list_id;
            $movie->vod_trysee = 0;
            $movie->save(false);

            // 新增一个播放分组
            $playGroup = new PlayGroup();
            $playGroup->vod_id = $movie->vod_id;
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

            echo $title . "新增" . PHP_EOL;
        } else {
            echo $title . "存在" . PHP_EOL;
        }
        return false;
    }
}