<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/8/6
 * Time: 15:39
 */

namespace console\script;


use common\models\MainClass;
use common\models\OttChannel;
use common\models\OttLink;
use common\models\SubClass;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class AnnaOtt extends base
{
    protected static $data;

    protected $currentMainClass;

    protected $currentSubClass;

    protected $map;

    public function __destruct()
    {
        $this->stdout('本次任务执行结束,若无输出则说明无新数据', Console::FG_GREEN);
    }

    /**
     * 处理直播数据
     */
    public function dealOTT()
    {
        $this->dealLiveTV();
        $this->sync();
    }

    private function dealLiveTV()
    {
        $accounts = [
            [
                'url' => "http://www.hdboxtv.net:8000/get.php?username=287994000090&password=287994000090&type=m3u_plus&output=ts",
                'mainClass' => null,
            ],
            [
                'url' => 'http://www.hdboxtv.net:8000/get.php?username=287994000091&password=287994000091&type=m3u_plus&output=ts',
                'mainClass' => 'ltn',
            ],
           [
                'url' => 'http://www.hdboxtv.net:8000/get.php?username=287994000092&password=287994000092&type=m3u_plus&output=ts',
                'mainClass' => 'br',
            ],
            [
                'url' => 'http://www.hdboxtv.net:8000/get.php?username=287994000093&password=287994000093&type=m3u_plus&output=ts',
                'mainClass' => null,
            ],
            [
                'url' => 'http://www.hdboxtv.net:8000/get.php?username=287994000094&password=287994000094&type=m3u_plus&output=ts',
                'mainClass' => null,
            ],
            [
                'url' => 'http://www.hdboxtv.net:8000/get.php?username=287994000099&password=287994000099&type=m3u_plus&output=ts',
                'mainClass' => null,
            ],

        ];



        $allData = [];
        foreach ($accounts as $url) {
            self::$data = $this->download($url['url']);
            $data = $this->initData();

            // 基本数据录入
            foreach ($data as $value) {

                $allData[] = $value;
                // 普通数据
                $mainClassID  = $this->_mainClass($value, $url['mainClass']);
                $subClassID   = $this->_subClass($value, $mainClassID);
                $channelID    = $this->_channel($value, $subClassID);
                $this->_link($value, $channelID);
            }
        }

        if (!empty($allData)) {
            ArrayHelper::multisort($allData, 'tvg-name', SORT_ASC);

            foreach ($allData as $key => $value) {
                // A-Z 数据
                $this->attachAZ($value);
                // HD SD
                $this->attachHDAndSD($value);
            }

            // 给A-Z重新排列频道好
            $this->sortAZ($allData);
        }
    }

    protected function sortAZ($data)
    {
        if (!empty($data)) {
            $subClass = SubClass::findOne(['name' => 'A-Z']);
            if ($subClass) {
                $channels = OttChannel::find()->where(['sub_class_id' => $subClass->id])->orderBy(['name' => SORT_ASC])->all();
                if (!empty($channels)) {
                    $channelNumber = 1;
                    foreach ($channels as $channel) {
                        $channel->channel_number = $channelNumber++;
                        $channel->save(false);
                    }
                }
            }
        }
    }

    /**
     * a-z
     * @param $value
     * @return bool
     */
    private function attachAZ($value)
    {
        $className = explode('|',$value['group-title']);
        if (!isset($className[1])) {
            return false;
        }

        $mainClassName = $className[1];
        $mainClass = MainClass::findOne(['name' => $mainClassName]);
        if ($mainClass && $mainClass->name == 'br') {
            $tmp['group-title'] = 'A-Z|br';
            $subClassID = $this->_subClass($tmp, $mainClass->id);
            $channelID = $this->_channel($value, $subClassID);
            $this->_link($value, $channelID);
        }

        return false;
    }

    /**
     * hd sd
     * @param $value
     * @return bool
     */
    private function attachHDAndSD($value)
    {
        $className = explode('|',$value['group-title']);
        if (!isset($className[1])) {
            return false;
        }

        $mainClassName = $className[1];
        $mainClass = MainClass::findOne(['name' => $mainClassName]);
        if ($mainClass && $mainClass->name == 'br') {
            if (strpos($value['tvg-name'], 'HD') !== false) {
                $tmp['group-title'] = 'Canais HD|br';
                $subClassID = $this->_subClass($tmp, $mainClass->id);
                $channelID = $this->_channel($value, $subClassID);
                $this->_link($value, $channelID);
            } elseif (strpos($value['tvg-name'], '4K') == false
                      &&
                      strpos($value['tvg-name'], '4k') == false) {
                $tmp['group-title'] = 'Canais SD|br';
                $subClassID = $this->_subClass($tmp, $mainClass->id);
                $channelID = $this->_channel($value, $subClassID);
                $this->_link($value, $channelID);
            }
        }

        return false;
    }

    /**
     * 一级分类
     * @param $value
     * @param $assignMainClass string 可能指定的分类
     * @return bool|int
     */
    private function _mainClass($value, $assignMainClass)
    {
        $className = explode('|',$value['group-title']);
        if (!isset($className[1])) {
            return false;
        }

        if (is_null($assignMainClass)) {
            $this->currentMainClass = $mainClassName = $className[1];
        } else {
            $this->currentMainClass = $mainClassName = $assignMainClass;
        }

        $mainClass = MainClass::findOne(['name' => $mainClassName]);

        if (is_null($mainClass)) {
            $mainClass = new MainClass();
            $mainClass->name = $mainClassName;
            $mainClass->zh_name = $mainClassName;
            $mainClass->save(false);
        }

        return $mainClass->id;
    }

    /**
     * 二级分类
     * @param $value
     * @param $mainClassID
     * @param $split boolean 是否需要分割符号
     * @return bool|int
     */
    private function _subClass($value, $mainClassID, $split = true)
    {
        $className = explode('|',$value['group-title']);

        if ($split == true && !isset($className[1])) {
            return false;
        }

        $this->currentSubClass = $subClassName = $className[0];
        $subClass = SubClass::findOne(['name' => $subClassName, 'main_class_id' => $mainClassID]);

        if (is_null($subClass)) {
            $subClass = new SubClass();
            $subClass->name = $subClassName;
            $subClass->zh_name = $subClassName;
            $subClass->keyword = $subClassName;
            $subClass->main_class_id = $mainClassID;
            $subClass->save(false);
        }

        return $subClass->id;
    }

    /**
     * 增加频道号
     * @param $value
     * @param $subClassID
     * @return bool|int
     */
    private function _channel($value, $subClassID)
    {
        if ($subClassID == false)  return false;

        //查找频道
        $channel = OttChannel::findOne([
            "name"          => $value['tvg-name'],
            'sub_class_id'  => $subClassID
        ]);

        $this->map[$this->currentMainClass][$this->currentSubClass][] = $value['tvg-name'];

        //新增频道
        if (empty($channel)) {
           $channel = new OttChannel();
           $channel->sub_class_id = $subClassID;
           $channel->name = $value['tvg-name'];
           $channel->zh_name = $value['tvg-name'];
           $channel->keywords = $value['tvg-name'];

           if ($value['tvg-logo']) $channel->image = $value['tvg-logo'];
           // 判断是否有HD 有的话去掉
           $alias = preg_replace('/\s*HD/', '', $value['tvg-name']);
           $channel->alias_name = $alias;
           $channel->save(false);
           $this->stdout("直播新增频道：" . $value['tvg-name'].PHP_EOL, Console::FG_BLUE);

        } else if ($channel->image != $value['tvg-logo'] && !empty($value['tvg-logo'])) {
            $channel->image = $value['tvg-logo'];
            $channel->save(false);
            $this->stdout("更新直播频道：" . $value['tvg-name'].PHP_EOL, Console::FG_BLUE);
        }

        return $channel->id;
    }

    /**
     * 新增链接
     * @param $value
     * @param $channelID
     * @return bool|int
     */
    private function _link($value, $channelID)
    {
        if ($channelID == false) {
            return false;
        }

        $Link = OttLink::find()->where(['channel_id' => $channelID])
                               ->one();

        //新增链接
        if (is_null($Link)) {
            $Link = new OttLink();
            $Link->channel_id = $channelID;
            $Link->link = $value['ts'];
            $Link->source = 'file';
            $Link->use_flag = 1;
            $Link->method = 'null';
            $Link->decode = 1;
            $Link->save(false);
        } else if ($Link->link !== $value['ts']) {
            echo "updated link" .PHP_EOL;
            $Link->link = $value['ts'];
            $Link->save();
        }

        return $Link->id;
    }

    protected function sync()
    {
        $this->map;

        // 遍历每一个一级分类
        if (empty($this->map)) {
            return false;
        }

        foreach ($this->map as $mainClassName => $fileSubClassArr) {
            $mainClass = MainClass::find()->where(['name' => $mainClassName])
                                          ->with('sub')
                                          ->asArray()
                                          ->one();
            if (empty($mainClass)) {
                return false;
            }

            if (!empty($mainClass['sub'])) {
                foreach ($mainClass['sub'] as $dbSubClass) {
                    if (!array_key_exists($dbSubClass['name'], $fileSubClassArr)) {
                        echo "数据库中不存在二级分类{$dbSubClass['name']} 已删除" . PHP_EOL;
                        SubClass::findOne($dbSubClass['id'])->delete();
                    } else {
                        $dbChannelNames = OttChannel::find()->select('name')->where(['sub_class_id' => $dbSubClass['id']])->column();
                        if ($dbChannelNames) {
                            foreach ($dbChannelNames as $dbChannelName) {
                                if (!in_array($dbChannelName, $fileSubClassArr[$dbSubClass['name']])) {
                                    OttChannel::findOne(['sub_class_id' => $dbSubClass['id'], 'name' => $dbChannelName])->delete();
                                    echo "删除频道 {$dbChannelName}" . PHP_EOL;
                                }
                            }
                        }

                    }
                }
            }
        }
    }

    private static function get($data, $default = null)
    {
        if (isset($data[0]) && !empty($data[0])) {
            return trim($data[0]);
        }

        if (is_null($default)) {
            return $default;
        }

        return $default;
    }

    private function download($url)
    {
        $this->stdout("下载文件".PHP_EOL);
        try {
            $data = file_get_contents($url);
            $this->stdout("下载文件结束" . PHP_EOL);
            return $data;

        } catch (\Exception $e) {
            $this->stdout("下载文件失败" . PHP_EOL);
            return false;
        }
    }

    /**
     * @param string $type 'ott|iptv'
     * @return array
     */
    private function initData($type = "ott")
    {
        $data = self::$data;
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

            $preg['tvg-id'] = self::get($tvg_id);
            $preg['tvg-name'] = strpos( self::get($tvg_name), '|') ? strstr(self::get($tvg_name), '|', true) : self::get($tvg_name) ;
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
}