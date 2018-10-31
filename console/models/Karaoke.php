<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/10/15
 * Time: 11:31
 */

namespace console\models;

use backend\models\Karaoke as Basic;

class Karaoke extends Basic
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

    public function collect($title, $url, $image, $info, $area)
    {
        $data = Karaoke::findOne(['albumName' => $title]);

        if (empty($data)) {
            $title = trim($title);
            $karaoke = new Karaoke();

            $karaoke->url = $url;
            $karaoke->albumName = $title;
            $karaoke->albumImage = $image;
            $karaoke->info = $info;
            $karaoke->area = $this->area;

            $karaoke->save(false);
            echo $title . "新增" . PHP_EOL;
        } else {
            echo $title . "存在" . PHP_EOL;
        }

        return false;
    }
}