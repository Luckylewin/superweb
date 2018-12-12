<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 14:42
 */

namespace console\collectors\local;

use Yii;
use yii\base\Model;
use common\models\Type;
use common\models\Vod;
use console\collectors\profile\ProfilesSearcher;
use console\collectors\common;
use common\components\FileHelper;

class VodCollector extends common
{
    public $directory = [
        [   'dir'      => '/home/newpo/pinyin/movie/',
            'playpath' => '/vod/movie',
            'type'     => 'movie'
        ]
    ];

    public $address = 'http://vod.newpo.cn:8080';
    public $playPath;
    public $type;
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function setPlayPath($path)
    {
        $this->playPath = $path;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function doCollect()
    {
        $this->clearOldData();

        foreach ($this->directory as $directory) {
            if (is_dir($directory['dir']) == false) {
                $this->color("不存在目录: {$directory['dir']}", 'ERROR');
                return false;
            }

            $this->setPlayPath($directory['playpath']);
            $this->setType($directory['type']);
            $this->getFileList($directory['dir']);

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

    protected function getEpisodeNumber($fileName)
    {
        preg_match('/\d+/', str_replace('mp4','', $fileName), $episodeNum);

        if ($this->type == 'movie') {
            return 1;
        } else {
            return $episodeNum[0]??1;
        }
    }

    protected function getEpisodes($path)
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
                    $fileName = $linkMatch[0];
                    $episodes[] =  [
                        'title'   => '全集',
                        'episode' => $this->getEpisodeNumber($fileName),
                        'url'     => $this->getLink($path, $fileName),
                    ];
                }
            }
        }

       return $episodes;
    }

    protected function getLink($path,$fileName)
    {
        $dirName = basename($path);
        return $this->address . $this->playPath . '/'. $dirName .'/' . $fileName . '/index.m3u8';
    }

    /**
     * @param $directory
     * @return array
     */
    protected function getFileList($directory)
    {
        $mediaArr = [];
        $list = FileHelper::scandir($directory);
        if (!empty($list)) {
            foreach ($list as $fileName) {
                $path = $directory . $fileName;
                $data = $this->getProfile($path);
                $data['links'] = $this->getEpisodes($path);

                if (!empty($data) && !empty($data['links']) && is_null(Vod::findOne(['vod_name' => $data['vod_name']]))) {

                    if ($profile = ProfilesSearcher::search($data['vod_name'], ['language' => 'zh-CN'])) {
                        $profile['vod_language'] = $data['vod_language'];
                        $profile['vod_area'] = $data['vod_area'];
                        $data = array_merge($data, $profile);
                        $data['vod_fill_flag'] = 1;
                    }

                    $mediaArr[] = $data;
                    $this->saveToDb($data);
                }
            }
        }

        return $mediaArr;
    }

    // 清除旧数据
    private function clearOldData()
    {
        $this->color("清除旧数据");
        Yii::$app->db->createCommand('truncate table iptv_vod')->query();
        Yii::$app->db->createCommand('truncate table iptv_play_group')->query();
        Yii::$app->db->createCommand('truncate table iptv_vodlink')->query();
        $this->color("数据清除完毕");
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