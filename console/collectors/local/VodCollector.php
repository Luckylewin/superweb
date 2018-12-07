<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 14:42
 */

namespace console\collectors\local;

use common\models\Vod;
use yii\base\Model;
use common\models\Type;
use console\collectors\profile\OMDB;
use console\collectors\profile\profile;
use yii\helpers\Console;
use console\collectors\common;

class VodCollector extends common
{
    public $dir = '/home/lychee/huayu/movie/';
    public $address = 'http://vod.newpo.cn:8080/vod/movie';
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function doCollect()
    {
        if (is_dir($this->dir) == false) {
            Console::error("不存在目录: {$this->dir}");
        }

        $this->getFileList();
    }

    protected function getProfile($path)
    {
        if (!is_dir($path)) {
            return false;
        }

        $list = scandir($path);
        foreach ($list as $fileName) {
            preg_match('/(\S)+(?=(.jepg|.jpg|.png))/', $fileName, $match);
            if (!empty($match)) {
                return [
                    'vod_name' => $match[0],
                    'vod_pic' => $match[0] . $match[2],
                    'vod_area' => Type::AREA_CHINA,
                    'vod_language' => Type::LANGUAGE_CHINESE
                ];
            }
        }

        return false;
    }

    protected function getEpisodes($path)
    {
        if (!is_dir($path)) {
            return false;
        }

        $episodes = [];
        $list = scandir($path);
        foreach ($list as $fileName) {
            preg_match('/(\S)+(?=(.jepg|.jpg|.png))/', $fileName, $match);
            if (!empty($match)) {
                $episodes[] =  [
                    'title'   => '全集',
                    'episode' => '1',
                    'pic'     => $match[0] . $match[2],
                    'url'     =>  $this->getLink($path),
                ];
            }
        }

       return $episodes;
    }

    protected function getLink($path)
    {
        $dirName = basename($path);
        return $this->address . '/'. $dirName .'/' . $dirName . '.mp4/index.m3u8';
    }

    protected function getFileList()
    {
        $dirArr = [];
        $list = scandir($this->dir);
        foreach ($list as $fileName) {
            if (!in_array($fileName, ['.', '..'])) {
                $path = $this->dir . $fileName;
                $data = $this->getProfile($path);

                if (is_null(Vod::findOne(['vod_name' => $data['vod_name']]))) {
                    $profile = profile::search($data['vod_name']);
                    if ($profile == false) {
                        $profile = $data = OMDB::findByTitle($data['vod_name']);
                    }

                    if ($profile) {
                        $data = array_merge($data, $profile);
                        $data['vod_fill_flag'] = 1;
                    }

                    if ($data) {
                        $data['links'] = $this->getEpisodes($path);
                        $dirArr[] = $data;
                    }

                    sleep(1);
                    $this->createVod($data, 'local');
                }
            }
        }

    }
}