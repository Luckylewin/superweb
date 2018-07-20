<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/5
 * Time: 15:31
 */

namespace console\controllers;

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

class ClientController extends Controller
{
    public $accounts = [
        '287994000050' =>  ['en' => 'English', 'zh' =>'英语'],
        '287994000051' =>  ['en' => 'Portuguese', 'zh' =>'葡萄牙语'],
        '287994000052' =>  ['en' => 'Spanish', 'zh' =>'西班牙语'],
        '287994000053' =>  ['en' => 'Arabic', 'zh' =>'阿拉伯语']
    ];

    public function actionAnnaIptv()
    {
        $this->clearOldData();

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
                $this->saveNewType($data);
            }

            // 处理电视剧
            foreach ($originData as $file) {
                $data = $this->initData($file['file'], 'iptv', 'program');
                $this->saveNewProgramData($data, $file['lang']['en']);
                $this->saveNewType($data);
            }

        }

        $this->saveNewLang();

        if ($originData) {
            // 更新电影的资料
            foreach ($originData as $file) {
                $data = $this->initData($file['file'], 'iptv', 'movie');
                $this->saveMovieProfile($data, $file['lang']['en']);
            }
        }

    }

    // 清除旧数据
    public function clearOldData()
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
    public function saveNewLang()
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
    public function saveNewType($data)
    {
       if (empty($data)) {
            return $this->stdout("没有数据", Console::FG_CYAN);
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
    public function saveNewProgramData($data, $lang)
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
    public function saveNewMovieData($data, $lang)
    {
        // 找到电影分类
        $vodList = VodList::findOne(['list_name' => '电影']);

        if (is_null($vodList)) {
            return $this->stdout("请在点播下新增电影分类" . PHP_EOL, Console::BG_RED);
        }

        if (is_null($data)) {
            return $this->stdout("没有电影数据" . PHP_EOL, Console::BG_RED);
        }

        foreach ($data as $val) {
            $vod = $this->getVod($vodList->list_id,  $val['tvg-name'],  $val['group-title'], $val['tvg-logo'], $lang);
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
    public function saveMovieProfile($data, $lang)
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
            $this->stdout("帐号{$username}下载失败".PHP_EOL ,Console::BG_RED);
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
                }
            }

            $preg['tvg-id'] = self::get($tvg_id);
            $preg['tvg-logo'] = self::get($tvg_logo);
            $preg['group-title'] = self::get($group_title);
            $preg['ts'] = iconv("ASCII", "UTF-8", self::get($ts));
            $preg['other'] = self::get($other);
            $preg['type'] = strpos($preg['ts'], 'ts') !== false ? 'ott' : "iptv";

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
     * @return array|Vod|null|\yii\db\ActiveRecord
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
    private function attachLink(Vod $vod, $url, $episode = 0)
    {
        // 查找是否存在
        $link = Vodlink::findOne(['video_id' => $vod->vod_id, 'url' => $url]);
        if (empty($link)) {
            $link = new Vodlink();
            $link->url = $url;
            $link->episode = $episode;
            $vod->link('vodLinks', $link);

            $this->stdout("新增链接{$url}" . PHP_EOL, Console::FG_BLUE);
        }
    }

    private function fillWithMovieProfile(Vod $vod)
    {
        if ($data = profile::search($vod->vod_name)) {
            foreach ($data as $field => $value) {
                if (!in_array($field, ['vod_pic', 'vod_language'])) {
                    $vod->$field = $value;
                }
            }

            $vod->save(false);
            $this->stdout("更新影片{$vod->vod_name}数据" . PHP_EOL, Console::FG_BLUE);
            mt_rand(0,1) && sleep(1);
        }
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