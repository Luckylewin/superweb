<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/7
 * Time: 13:26
 */

namespace console\script;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use backend\models\IptvType;
use backend\models\IptvTypeItem;
use common\components\BaiduTranslator;
use console\collectors\profile\OMDB;
use common\models\Vod;
use common\models\Vodlink;
use common\models\VodList;
use console\models\common;
use console\collectors\profile\MOVIEDB;
use backend\models\PlayGroup;
use console\collectors\profile\ProfilesSearcher;

/**
 * 安娜IPTV处理脚本
 * Class AnnaIptv
 * @package console\script
 */
class AnnaIptv extends base
{
    /**
     * @var Controller
     */
    public $controller;

    protected $program = [];

    private $accounts = [
        '287994000050' =>  ['en' => 'English', 'zh' =>'英语'],
        '287994000051' =>  ['en' => 'Portuguese', 'zh' =>'葡萄牙语'],
        '287994000052' =>  ['en' => 'Spanish', 'zh' =>'西班牙语'],
        '287994000053' =>  ['en' => 'Arabic', 'zh' =>'阿拉伯语']
    ];

    public function dealIPTV()
    {
        // $this->clearOldData();
        $this->saveDataFromArray($this->getOriginData());
        $this->saveNewLang();
        $this->clearDeleteData();
        $this->updateProfile();
    }

    /**
     * 下载文件 变成数组 保存
     * @return array
     */
    private function getOriginData()
    {
        $originData = [];
        foreach ($this->accounts as $account => $lang) {
            $file = $this->download($account);
            $originData[] = ['file' => $file, 'lang' => $lang];
        }

        return $originData;
    }

    /**
     * 整理后的数据保存到数据库
     * @param $originData
     */
    private function saveDataFromArray($originData)
    {
        if ($originData) {
            // 处理电影
            foreach ($originData as $file) {
                $data = $this->initData($file['file'], 'iptv', 'movie');
                $this->saveNewMovieData($data, $file['lang']['en']);
            }

            // 处理电视剧
            foreach ($originData as $file) {
                $data = $this->initData($file['file'], 'iptv', 'program');
                $this->saveNewProgramData($data, $file['lang']['en']);
            }
        }
    }

    /**
     * 清除远程服务器文件没有的数据
     */
    private function clearDeleteData()
    {
        $tvg_arr = array_unique($this->program);

        if (!empty($tvg_arr)) {
            $vodList = Vod::find()->all();
            foreach ($vodList as $vod) {
                if (!in_array($vod->vod_name, $tvg_arr)) {
                    $this->stdout("删除远程文件没有的数据{$vod->vod_name}" . PHP_EOL, Console::FG_GREY);
                    $vod->delete();
                }
            }
        }
    }

    // 清除旧数据
    private function clearOldData()
    {
        $this->stdout("清除旧数据" . PHP_EOL, Console::FG_PURPLE);
        Yii::$app->db->createCommand('truncate table iptv_vod')->query();
        Yii::$app->db->createCommand('truncate table iptv_play_group')->query();
        Yii::$app->db->createCommand('truncate table iptv_vodlink')->query();
        $this->stdout("数据清除完毕" . PHP_EOL, Console::FG_PURPLE);
    }

    /**
     *  新增语言
     */
    private function saveNewLang()
    {
        $types = ['Movie', 'Serial'];

        foreach ($types as $type) {
            // 找到电影分类
            $vodList = $this->getGenre($type);

            if (is_null($vodList)) {
                continue;
            }

            // 查找field
            $langField = $this->getConditionField($vodList->list_id);

            if ($langField) {
                // 新增
                foreach ($this->accounts as $area) {
                    // 查询是否有
                    $exist =IptvTypeItem::find()->where(['type_id' => $langField->id, 'name' => $area['en']])->exists();
                    if ($exist == false) {
                        $item = new IptvTypeItem();
                        $item->type_id = $langField->id;
                        $item->name = $area['en'];
                        $item->zh_name = $area['zh'];
                        $item->save(false);
                    }
                }

                // 删除
                $areas = IptvTypeItem::find()->where(['type_id' => $langField->id])->all();
                foreach ($areas as $area) {
                    // 查询是否有影片引用了这个area
                    $exist = Vod::find()->where(['vod_language' => $area->name])->exists();
                    if ($exist == false) {
                        // 删除这个area
                        if ($area instanceof IptvTypeItem) {
                            $area->delete();
                        }
                    }
                }
            }
        }
    }

    //新增电视剧数据
    private function saveNewProgramData($data, $lang)
    {
        // 找到电视剧分类
        $vodList = $this->getGenre('Serial');

        if (is_null($vodList)) {
            $this->stdout("请新增电视剧类型" . PHP_EOL, Console::BG_RED);
        }

        if (is_null($data)) {
            $this->stdout("没有影片数据" . PHP_EOL, Console::BG_RED);
        }

        $total = count($data);

        foreach ($data as $key => $val) {
            $sort = $total - $key;
            $vod = $this->getOrSetVod($vodList->list_id,  $val['tvg-name'],  $val['group-title'], $val['tvg-logo'], $lang, $sort,'电视剧');
            $group = $this->attachGroup($vod);

            $url = $val['ts'];
            $episode = isset($val['episode']) ? $val['episode'] : 1;

            $this->attachLink($vod, $group, $url, $episode);
        }

    }

    /**
     * 新增电影数据
     * @param $data
     * @param $lang
     * @return bool|int
     */
    private function saveNewMovieData($data, $lang)
    {
        // 找到电影分类
        $vodList = $this->getGenre('Movie');

        if (is_null($vodList)) {
            return $this->stdout("请在点播下新增电影分类" . PHP_EOL, Console::BG_RED);
        }

        if (is_null($data)) {
            return $this->stdout("没有电影数据" . PHP_EOL, Console::BG_RED);
        }

        $total = count($data);
        foreach ($data as $key => $val) {
            $sort = $total - $key;
            $vod = $this->getOrSetVod($vodList->list_id,  $val['tvg-name'],  $val['group-title'], $val['tvg-logo'], $lang, $sort);
            // 如果更改了类别 则更新
            if ($vod->vod_type != $val['group-title'] || $vod->vod_keywords != $val['group-title']) {
                $vod->vod_type = $val['group-title'];
                $vod->vod_keywords = $val['group-title'];
                $vod->save(false);
            }

            $group = $this->attachGroup($vod);
            $this->attachLink($vod,$group, $val['ts']);
        }

        return true;
    }

    /**
     * 获取分类
     * @param $mode
     * @return array|VodList|null|\yii\db\ActiveRecord
     */
    private function getGenre($mode)
    {
        if ($mode == 'Movie') {
            return $vodList = VodList::find()->where(['list_name' => '电影'])
                ->orWhere(['list_name' => 'Movie'])
                ->orWhere(['list_dir' => 'Movie'])
                ->one();
        }

        if ($mode == 'Serial') {
            return $vodList = VodList::find()->where(['list_name' => '电视剧'])
                ->orWhere(['list_name' => 'Serial'])
                ->orWhere(['list_dir' => 'Serial'])
                ->one();
        }

        return null;
    }

    /**
     * 更新影片数据
     */
    private function updateProfile()
    {
        $vods = Vod::find()->all();
        foreach ($vods as $vod) {
            $data = ProfilesSearcher::quickSearchInDB($vod->vod_name);
            $this->fillWithMovieProfile($vod,$data);
        }

        foreach ($vods as $vod) {
            $data = ProfilesSearcher::search($vod->vod_name);
            $this->fillWithMovieProfile($vod,$data);
        }
    }



    /**
     * 下载文件
     * @param $username
     * @return bool|string
     */
    private function download($username)
    {
        $password = $username;

        // 初始化数据
        try {
            $url = "http://www.hdboxtv.net:8000/get.php?username={$username}&password={$password}&type=m3u_plus&output=ts";
            $this->stdout("下载文件 {$url}" . PHP_EOL, Console::FG_BLUE);
            $data = file_get_contents($url);
            if (empty($data)) {
                return false;
            }

            return $data;
        } catch (\Exception $e) {
            $this->stdout("{$url} 下载失败" . PHP_EOL ,Console::FG_RED);
            return false;
        }
    }

    /**
     * 匹配字符串到数组
     * @param mixed $data
     * @param string $type 'ott|iptv'
     * @param string $mode 'movie|program'
     * @return array
     */
    private function initData($data, $type = "ott", $mode = 'movie')
    {
        $data = preg_split('/#EXTINF:-1/',$data);
        $array = [];

        foreach ($data as $item) {
            $preg = [];

            preg_match('/(?<=tvg-id\=")[^"]+/', $item, $tvg_id);
            preg_match('/(?<=tvg-name\=")[^"]+/', $item, $tvg_name);
            preg_match('/(?<=tvg-logo\=")[^"]+/', $item, $tvg_logo);
            preg_match('/(?<=group-title\=")[^"]+/i', $item, $group_title);
            preg_match('/\S+\.(ts|mp4|mkv|rmvb|avi)/', $item, $ts);
            preg_match('/(?<=",)[^\r\n]+/', $item, $other);


            $orinName = self::get($tvg_name);

            if ($this->isShouldContinue($mode, $orinName) == false) {
                continue;
            }

            $preg['tvg-id']      = self::get($tvg_id);
            $preg['tvg-logo']    = self::get($tvg_logo);
            $preg['group-title'] = self::get($group_title);
            $preg['other']       = self::get($other);
            $preg['ts']          = $this->changeCharset(self::get($ts));
            $preg['type']        = $this->getIptvOrOtt($preg['ts']);
            $preg['episode']     = $this->getEpisode($orinName);
            $preg['tvg-name']    = $this->getRightVodName($orinName);
            $preg['group-title'] = $this->resetGroup($preg['group-title']);

            if ($mode == 'program') {
                if (strpos($orinName, '|') !== false) {
                    $preg['tvg-name'] = $this->resetName($orinName);
                }
            }

            $this->rememberNewVod($preg['tvg-name']);

            if (!$preg['ts']) continue;

            $array[] = $preg;
        }

        foreach ($array as $key => $value) {
            if ($value['type'] != $type) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    private static function get($data)
    {
        if (isset($data[0]) && !empty($data[0])) {
            return trim($data[0]);
        }
        
        return is_array($data) && empty($data) ? '' : $data;
    }

    private function getEpisode($tvg_name)
    {
        // 匹配季节 S E
        $tvg_name = preg_replace('/(?<=E)P:?(?=\d+)/','', $tvg_name);
        preg_match('/(?<=S)\d{1,2}/',$tvg_name, $seasonMatch);
        preg_match('/(?<=E)\d{2,3}/',$tvg_name, $episodeMatch);

        if (isset($seasonMatch[0]) && isset($episodeMatch[0]) && !empty($episodeMatch[0])) {
            return $episodeMatch[0];
        }

        return 1;
    }

    private function getRightVodName($tvg_name)
    {
        $season = $this->getSeason($tvg_name);

        // 去掉可能错误的格式
        $tvg_name = strpos( self::get($tvg_name), '|') ? strstr($tvg_name, '|', true) : $tvg_name ;
        $tvg_name = preg_replace('/S\d+(?=EP)/','', $tvg_name);
        $tvg_name = preg_replace('/(?<=E)P:?(?=\d+)/','', $tvg_name);

        $tvg_name = preg_replace('/E\d+/','', $tvg_name);
        $tvg_name = preg_replace('/S\d+/','',$tvg_name);
        $tvg_name = preg_replace ("/\s(?=\s)/","\\1", $tvg_name);

        $season = $season ? " " . $season : "";
        $name = trim($tvg_name) . $season;

        return $name;
    }

    private function getSeason($str)
    {
        preg_match('/S\d+/',$str, $season);

        return isset($season[0]) ? $season[0] : '';
    }

    private function changeCharset($str)
    {
        return iconv("ASCII", "UTF-8", $str);
    }

    private function getIptvOrOtt($link)
    {
        return strpos($link, 'ts') !== false ? 'ott' : "iptv";
    }

    private function isShouldContinue($mode, $tvg_name)
    {
        // 判断是否为电视剧
        $isProgram = preg_match('/S\d{1,3}/', $tvg_name);

        if ($mode == 'movie' && $isProgram) {
            return false;
        } else if($mode == 'program' && !$isProgram) {
            return false;
        }

        return true;
    }

    private function resetName($tvg_name)
    {
        preg_match('/S\d+/', $tvg_name, $season);

        $season = self::get($season);
        if ($season) {
            $tvg_name = preg_replace('/S\d{1,3}/', '', $tvg_name);
            return strstr($tvg_name, '|', true) . " ". $season;
        }

        return $tvg_name;
    }

    private function resetGroup($group_title)
    {
        if (in_array($group_title, ['Portuguese Movies', 'Espanol Movies'])) {
            return 'OTHER';
        }

        return $group_title;
    }

    private function rememberNewVod($tvg_name)
    {
        $this->program[] = $tvg_name;
    }

    /**
     * 返回一个影片，不存在则新增
     * @param $cid
     * @param $name
     * @param $keyword
     * @param $picture
     * @param $language
     * @param $sort
     * @param $type
     * @return Vod
     */
    private function getOrSetVod($cid, $name, $keyword = null, $picture = null, $language = null, $sort = null, $type = '电影')
    {
        $vod = Vod::find()->where(['vod_cid' => $cid ,'vod_name' => $name])->one();

        if (is_null($vod)) {
            $vod = new Vod();
            $vod->vod_cid = $cid;
            $vod->vod_name = $name;
            $vod->vod_type = $keyword;
            $vod->vod_keywords = $keyword;
            $vod->vod_pic = $picture;
            $vod->vod_letter = common::getFirstCharter($name);
            $vod->vod_language = $language;
            $vod->sort = $sort;
            $vod->vod_multiple = $type == '电影' ? '0' : '1';
            $vod->save(false);
            $this->stdout("新增{$type}{$name}" . PHP_EOL, Console::FG_YELLOW);

        } else if ($type == '电影' && $vod->vod_multiple != '0') {
            $vod->vod_multiple = 0;
            $vod->save(false);
        } else if ($type == '电视剧' && $vod->vod_multiple == '0') {
            $vod->vod_multiple = 1;
            $vod->save(false);
        }

        return $vod;
    }

    protected function attachGroup(Vod $vod)
    {
        // 查找是否存在一个default分组
        $playGroup = PlayGroup::find()->where(['vod_id' => $vod->vod_id, 'group_name' => 'default'])->limit(1)->one();
        if (is_null($playGroup)) {
            $playGroup = new PlayGroup();
            $playGroup->vod_id = $vod->vod_id;
            $playGroup->group_name = 'default';
            $playGroup->sort = 0;
            $playGroup->save(false);
        }

        return $playGroup;
    }

    /**
     * 关联链接
     * @param Vod $vod
     * @param PlayGroup $group
     * @param $url
     * @param int $episode
     */
    private function attachLink(Vod $vod,PlayGroup $group, $url, $episode = 1)
    {
        $baseName = basename($url);

        // 查找是否存在
        $link =  Vodlink::find()
                            ->where(['video_id' => $vod->vod_id])
                            ->where(['url' => $url])
                            ->one();

        if (!empty($link)) {
            if ($link->url !== $url) {
                $link->url = $url;
                $link->save(false);
                $this->stdout("更新链接{$url}" . PHP_EOL, Console::FG_BLUE);
            }
        } else {
            $link = new Vodlink();
            $link->url = $url;
            $link->episode = $episode;
            $link->group_id = $group->id;
            $vod->link('vodLinks', $link);

            // $this->stdout("新增链接{$url}" . PHP_EOL, Console::FG_GREEN);
        }
    }

    private function fillWithMovieProfile(Vod $vod, $data)
    {
        //the movie db
        if ($vod->vod_fill_flag) {
            return false;
        }

        if ($data) {
            $this->stdout("正在查询:{$vod->vod_name}" . PHP_EOL,Console::FG_GREY);
            foreach ($data as $field => $value) {
                if (!in_array($field, ['vod_name','vod_pic', 'vod_language', 'vod_keyword', 'vod_type'])) {
                    if ($vod->hasAttribute($field)) {
                        $vod->$field = $value;
                    }
                }
            }

            $vod->vod_fill_flag = 1;
            $vod->save(false);

            $this->stdout("更新影片：{$vod->vod_name}资料" . PHP_EOL, Console::FG_GREEN);
        }

        return true;
    }

    private function search($name)
    {
        if ($data = ProfilesSearcher::search($name)) {
            return $data;
        }

        // 尝试处理名字
        if ($pos = strpos($name, '(')) {
            $name = substr($name, 0, $pos);
            $name = trim($name);

            if ($data = ProfilesSearcher::search($name)) {

                return $data;
            }
        }

        //　包含年份
        if(preg_match('/\d{4}/', $name, $year)) {
            $name = preg_replace('/\d{4}/', '', $name);
            $name = trim($name);
            $year = current($year);

            if ($data = OMDB::searchByName($name, $year)) {
                return $data;
            } elseif ($data = MOVIEDB::likeSearch($name)) {
                return $data;
            }

        }

        // 包含点
        if (strpos($name, '.') !== false) {
            $name = strstr($name, '.', true);
            return OMDB::searchByName($name);
        }

        // 包含3D 4K
        if (preg_match('/[3D|4K|3d|4k]/', $name)) {
            $name = preg_replace('/[3D|4K|3d|4k]/', '', $name);
            $name = trim($name);

            if ($data = OMDB::searchByName($name)) {
                return $data;
            } elseif ($data = MOVIEDB::likeSearch($name)) {
                return $data;
            }
        }

        // 模糊搜索
        if ($data = MOVIEDB::likeSearch($name)) {
            return $data;
        }

        sleep(1);
        $this->stdout("找不到数据{$name}", Console::FG_RED);

        return false;
    }

    private function getConditionField($genre_id)
    {
        $iptvType = IptvType::find()->where(['field' => 'vod_language', 'vod_list_id' => $genre_id])->one();

        // 如果不存在 新增field
        if (is_null($iptvType)) {
            $iptvType = new IptvType();
            $iptvType->name = '语言';
            $iptvType->field = 'vod_language';
            $iptvType->vod_list_id = $genre_id;
            $iptvType->save();
        }

        return $iptvType;
    }
}
