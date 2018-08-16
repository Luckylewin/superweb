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
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use backend\models\IptvType;
use backend\models\IptvTypeItem;
use common\components\BaiduTranslator;
use common\models\Vod;
use common\models\Vodlink;
use common\models\VodList;
use console\models\common;
use console\models\profile;
use console\models\movie\OMDB;

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
    public $program = [];

    private $accounts = [
        '287994000050' =>  ['en' => 'English', 'zh' =>'英语'],
        '287994000051' =>  ['en' => 'Portuguese', 'zh' =>'葡萄牙语'],
        '287994000052' =>  ['en' => 'Spanish', 'zh' =>'西班牙语'],
        '287994000053' =>  ['en' => 'Arabic', 'zh' =>'阿拉伯语']
    ];

    public function dealIPTV()
    {
        $originData = [];
        foreach ($this->accounts as $account => $lang) {
            $file = $this->download($account);
            $originData[] = ['file' => $file, 'lang' => $lang];
        }

        if ($originData) {
            // 处理电影
            foreach ($originData as $file) {
                $data = $this->initData($file['file'], 'iptv', 'movie');
                $this->saveNewMovieData($data, $file['lang']['en']);
                //$this->saveNewType($data);
            }

            // 处理电视剧
            foreach ($originData as $file) {
                $data = $this->initData($file['file'], 'iptv', 'program');
                $this->saveNewProgramData($data, $file['lang']['en']);
                //$this->saveNewType($data);
            }
        }

        $this->saveNewLang();
        $this->clearDeleteData();

        if ($originData) {
            // 更新电影的资料
            foreach ($originData as $file) {
                $data = $this->initData($file['file'], 'iptv', 'movie');
                $this->saveMovieProfile($data, $file['lang']['en']);
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
        $delType = ['电影', '电视剧'];

        // 查找数据 删除链接
        foreach ($delType as $del) {
            $vodList = VodList::findOne(['list_name' => $del]);
            if ($vodList) {
                $vod = Vod::find()->where(['vod_cid' => $vodList->list_id])->all();
                foreach ($vod as $val) {
                    $vodName = $val->vod_name;
                    $val->delete();
                    $this->stdout("删除{$vodName}成功" . PHP_EOL, Console::FG_GREEN, Console::UNDERLINE);
                }
            }
        }

        Yii::$app->db->createCommand('optimize table iptv_vod')->query();
        Yii::$app->db->createCommand('optimize table iptv_vodlink')->query();
    }

    /**
     *  新增语言
     */
    private function saveNewLang()
    {
        $types = ['电影', '电视剧'];

        foreach ($types as $type) {
            // 找到电影分类
            $vodList = VodList::findOne(['list_name' => $type]);
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



    // 新增类型
    private function saveNewType($data)
    {
        if (empty($data)) {
            return $this->stdout("data没有数据" . PHP_EOL, Console::FG_CYAN);
        }

        $iptvTypes = ['电影', '电视剧'];
        foreach ($iptvTypes as $iptvType) {
            $types = array_unique(ArrayHelper::getColumn($data, 'group-title'));
            $vodList = VodList::findOne(['list_name' => $iptvType]);
            $type = $this->getType($vodList->list_id);

            foreach ($types as $val) {
                $this->getItem($val, $type->id);
            }

            $items = IptvTypeItem::find()->where(['type_id' => $type->id])->all();

            foreach ($items as $item) {
                // 删除没有的引用
                if (Vod::find()->where(['like', 'vod_type', $item->name])->exists() == false) {
                    $item->delete();
                }
            }
        }
    }

    //新增电视剧数据
    private function saveNewProgramData($data, $lang)
    {
        // 找到电视剧分类
        $vodList = VodList::findOne(['list_name' => '电视剧']);
        if (is_null($vodList)) {
            $this->stdout("请新增电视剧类型" . PHP_EOL, Console::BG_RED);
        }

        if (is_null($data)) {
            $this->stdout("没有电影数据" . PHP_EOL, Console::BG_RED);
        }

        foreach ($data as $val) {
            $vod = $this->getVod($vodList->list_id,  $val['tvg-name'],  $val['group-title'], $val['tvg-logo'], $lang, '电视剧');
            $url = $val['ts'];
            $episode = isset($val['episode']) ? $val['episode'] : 0;
            $this->attachLink($vod, $url, $episode);
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
        $vodList = VodList::findOne(['list_name' => '电影']);

        if (is_null($vodList)) {
            return $this->stdout("请在点播下新增电影分类" . PHP_EOL, Console::BG_RED);
        }

        if (is_null($data)) {
            return $this->stdout("没有电影数据" . PHP_EOL, Console::BG_RED);
        }

        foreach ($data as $key => $val) {
            $vod = $this->getVod($vodList->list_id,  $val['tvg-name'],  $val['group-title'], $val['tvg-logo'], $lang);
            if ($vod->sort != 0) {
                $vod->sort = $key + 1;
                $vod->save(false);
            }
            //$this->fillWithMovieProfile($vod);
            $this->attachLink($vod, $val['ts']);
        }

        return true;
    }

    /**
     * 更新影片数据
     * @param $data
     * @param $lang
     * @return bool|int
     */
    private function saveMovieProfile($data, $lang)
    {
        if (empty($data)) {
            return $this->stdout("没有电影数据" . PHP_EOL, Console::BG_RED);
        }

        // 找到电影分类
        $vodList = VodList::findOne(['list_name' => '电影']);

        foreach ($data as $val) {
            $vod = $this->getVod($vodList->list_id,  $val['tvg-name'],  $val['group-title'], $val['tvg-logo'], $lang);
            $this->fillWithMovieProfile($vod);
        }

        return true;
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    private function download($username)
    {
        $password = $username;

        // 初始化数据
        try {
            $url = "http://www.hdboxtv.net:8000/get.php?username={$username}&password={$password}&type=m3u_plus&output=ts";
            $data = file_get_contents($url);
            if (empty($data)) {
                return false;
            }

            return $data;

        } catch (\Exception $e) {
            $this->stdout("帐号{$username}下载失败".PHP_EOL ,Console::FG_RED);
            return false;
        }

    }

    /**
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
            preg_match('/\S+\.(ts|mp4|mkv|rmvb)/', $item, $ts);
            preg_match('/(?<=",)[^\r\n]+/', $item, $other);

            // 判断是否为电视剧
            $isProgram = preg_match('/\s*S\d+\s*E\d+\s*/', self::get($tvg_name));



            if ($mode == 'movie' && $isProgram) {
                continue;
            } else if($mode == 'program' && !$isProgram) {
                continue;
            }

            $preg['tvg-name'] = strpos( self::get($tvg_name), '|') ? strstr(self::get($tvg_name), '|', true) : self::get($tvg_name) ;

            if ($mode == 'program') {
                // 重新处理名称
                if (strpos( self::get($tvg_name), '|') !== false) {
                    preg_match('/S\d+/', self::get($tvg_name), $season);
                    $season = self::get($season);
                    if ($season) {
                        $preg['tvg-name'] = strstr(self::get($tvg_name), '|', true) . " ". $season;

                    }
                    preg_match('/(?<=E)\d+/', self::get($tvg_name), $episode);
                    $episode = self::get($episode);
                    if ($episode) {
                        $preg['episode'] = (int) $episode;
                    }

                    // 匹配季节 S E
                } else {
                    preg_match('/(S\d+)\s+(E\d+)/', self::get($tvg_name), $match);
                    if (isset($match[1]) && isset($match[2])) {
                        $season = $match[1];
                        $episode = $match[2];
                        $preg['tvg-name'] = trim(str_replace($episode, '', $preg['tvg-name']));
                        $preg['episode'] = (int) (str_replace('E', '', $episode));
                    }
                }
            }

            $this->program[] = $preg['tvg-name'];

            $preg['tvg-id'] = self::get($tvg_id);
            $preg['tvg-logo'] = self::get($tvg_logo);
            $preg['group-title'] = self::get($group_title);
            $preg['ts'] = iconv("ASCII", "UTF-8", self::get($ts));
            $preg['other'] = self::get($other);
            $preg['type'] = strpos($preg['ts'], 'ts') !== false ? 'ott' : "iptv";

            if (in_array($preg['group-title'], ['Portuguese Movies', 'Espanol Movies'])) {
                $preg['group-title'] = 'OTHER';
            }

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
        return null;
    }

    /**
     * 返回一个影片，不存在则新增
     * @param $cid
     * @param $name
     * @param $keyword
     * @param $picture
     * @param $language
     * @param $type
     * @return Vod
     */
    private function getVod($cid, $name, $keyword, $picture, $language, $type = '电影')
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
            $vod->sort = 0;
            $vod->save(false);
            $this->stdout("新增{$type}{$name}" . PHP_EOL, Console::FG_YELLOW);
        }

        return $vod;
    }

    /**
     * 关联链接
     * @param Vod $vod
     * @param $url
     * @param int $episode
     */
    private function attachLink(Vod $vod, $url, $episode = 1)
    {
        $baseName = basename($url);
        // 查找是否存在
        $link =  Vodlink::find()->where(['video_id' => $vod->vod_id])->andWhere(['LIKE', 'url', $baseName])->one();

        if (!empty($link)) {
            if ($link->url != $url) {
                $link->url = $url;
                $link->save(false);
                $this->stdout("更新链接{$url}" . PHP_EOL, Console::FG_BLUE);
            }
        } else {
            $link = new Vodlink();
            $link->url = $url;
            $link->episode = $episode;
            $vod->link('vodLinks', $link);

            $this->stdout("新增链接{$url}" . PHP_EOL, Console::FG_GREEN);
        }
    }

    private function fillWithMovieProfile(Vod $vod)
    {
        //the movie db
        if ($vod->vod_fill_flag) {
            $this->stdout("影片{$vod->vod_name}数据已完善，跳过" . PHP_EOL, Console::FG_YELLOW);
            return false;
        }

        if ($data = self::search($vod->vod_name)) {
            foreach ($data as $field => $value) {
                if (!in_array($field, ['vod_name','vod_pic', 'vod_language', 'vod_keyword', 'vod_type'])) {
                    if ($vod->hasAttribute($field)) {
                        $vod->$field = $value;
                    }
                }
            }

            $vod->vod_fill_flag = 1;
            $vod->save(false);
            $this->stdout("更新影片{$vod->vod_name}数据" . PHP_EOL, Console::FG_GREEN);
            sleep(1);
        }

        return true;
    }

    private function search($name)
    {
        $this->stdout("正在查询:{$name}" . PHP_EOL,Console::FG_GREY);

        if (($data = profile::search($name)) || ($data = OMDB::findByTitle($name))) {
            return $data;
        }

        // 尝试处理名字
        if ($pos = strpos($name, '(')) {
            $name = substr($name, 0, $pos);
            $name = trim($name);
            if ($data = OMDB::findByTitle($name)) {
                return $data;
            } elseif ($data = profile::likeSearch($name)) {
                return $data;
            }
        }

        //　包含年份
        if(preg_match('/\d{4}/', $name, $year)) {
            $name = preg_replace('/\d{4}/', '', $name);
            $name = trim($name);
            $year = current($year);

            if ($data = OMDB::findByTitle($name, $year)) {
                return $data;
            } elseif ($data = profile::likeSearch($name)) {
                return $data;
            }

        }

        // 包含点
        if (strpos($name, '.') !== false) {
            $name = strstr($name, '.', true);
            return OMDB::findByTitle($name);
        }

        // 包含3D 4K
        if (preg_match('/[3D|4K|3d|4k]/', $name)) {
            $name = preg_replace('/[3D|4K|3d|4k]/', '', $name);
            $name = trim($name);

            if ($data = OMDB::findByTitle($name)) {
                return $data;
            } elseif ($data = profile::likeSearch($name)) {
                return $data;
            }
        }

        // 模糊搜索
        if ($data = profile::likeSearch($name)) {
            return $data;
        }

        sleep(1);
        $this->stdout("找不到数据{$name}", Console::FG_RED);
        return false;
    }

    private function getType($cid)
    {
        $type = IptvType::find()->where(['field' => 'vod_type', 'vod_list_id' => $cid])->one();

        if (is_null($type)) {
            $type = new IptvType();
            $type->field = 'vod_type';
            $type->name = '类型';
            $type->vod_list_id = 'cid';
            $type->save(false);
        }

        return $type;
    }

    private function getItem($name, $type_id)
    {
        $item = IptvTypeItem::find()->where(['name' => $name, 'type_id' => $type_id])->exists();

        if ($item == false) {
            $item = new IptvTypeItem();
            $item->name = $name;
            $item->zh_name = BaiduTranslator::translate($name, 'en', 'zh');
            $item->type_id = $type_id;
            $item->sort = 0;
            $item->save(false);
            $this->stdout("新增{$name} 子类型" . PHP_EOL, Console::FG_GREEN, Console::UNDERLINE);
        }
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