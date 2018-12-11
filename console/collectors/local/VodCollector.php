<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 14:42
 */

namespace console\collectors\local;

use common\models\Vod;
use console\collectors\profile\ProfilesSearcher;
use yii\base\Model;
use common\models\Type;
use yii\helpers\Console;
use console\collectors\common;

class VodCollector extends common
{
    public $directory = [
        [ 'dir' => '/home/newpo/pinyin/movie/', 'playpath' => '/vod/movie']
    ];

    public $address = 'http://vod.newpo.cn:8080';

    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function doCollect()
    {
        foreach ($this->directory as $directory) {
            if (is_dir($directory['dir']) == false) {
                return Console::error("不存在目录: {$directory['dir']}");
            }

            $data = $this->getFileList($directory['dir'], $directory['playpath']);
            $this->saveToDb($data);
        }
    }

    protected function getProfile($path)
    {
        if (!is_dir($path)) {
            return false;
        }

        $list = scandir($path);
        foreach ($list as $fileName) {
            preg_match('/(\S)+(?=(.jepg|.jpg|.png))/', $fileName, $match);
            if (isset($match[0]) && !empty($match[0])) {
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

    protected function getEpisodes($path, $playPath)
    {
        if (!is_dir($path)) {
            return false;
        }

        $episodes = [];
        $list = scandir($path);

        foreach ($list as $fileName) {
            if (!in_array($fileName, ['.', '..'])) {
                preg_match('/.*mp4/', $fileName, $linkMatch);
                if (isset($linkMatch[0])) {
                    preg_match('/\d+/', $fileName, $episodeNum);

                    $fileName = $linkMatch[0];

                    $episodes[] =  [
                        'title'   => '全集',
                        'episode' => isset($episodeNum[0]) ? $episodeNum[0] : 1,
                        'url'     => $this->getLink($path, $playPath, $fileName),
                    ];
                }
            }
        }

       return $episodes;
    }

    protected function getLink($path, $playPath, $fileName)
    {
        $dirName = basename($path);
        return $this->address . $playPath . '/'. $dirName .'/' . $fileName . '/index.m3u8';
    }

    /**
     * @param $directory
     * @param $playPath
     * @return array
     */
    protected function getFileList($directory, $playPath)
    {
        $mediaArr = [];
        $list = scandir($directory);
        foreach ($list as $fileName) {
            if (!in_array($fileName, ['.', '..'])) {
                $path = $directory . $fileName;
                $data = $this->getProfile($path);

                if (is_null(Vod::findOne(['vod_name' => $data['vod_name']]))) {

                    if ($profile = ProfilesSearcher::search($data['vod_name'])) {
                        $profile['vod_language'] = $data['vod_language'];
                        $profile['vod_area'] = $data['vod_area'];
                        $data = array_merge($data, $profile);
                        $data['vod_fill_flag'] = 1;
                    }

                    if ($data) {
                        $data['links'] = $this->getEpisodes($path, $playPath);
                        
                        if (!empty($data['links'])) {
                            $mediaArr[] = $data;
                            $this->saveToDb($data);
                        }
                    }
                }
            }
        }

        return $mediaArr;
    }

    protected function saveToDb($data)
    {
        try {
            $this->createVod($data, 'local');
        } catch (\Exception $e) {
            $this->color("File:" . $e->getFile(),'ERROR');
            $this->color("Line:" . $e->getLine(),'ERROR');
            $this->color("Message:" . $e->getMessage(),'ERROR');
        }
    }
}