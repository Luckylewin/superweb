<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/5
 * Time: 15:31
 */

namespace console\controllers;


use backend\models\IptvType;
use backend\models\IptvTypeItem;
use common\components\BaiduTranslator;
use common\models\Vod;
use common\models\Vodlink;
use common\models\VodList;
use yii\console\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class ClientController extends Controller
{
    public function actionAnnaIptv()
    {
         $data = $this->initData($this->download(), 'iptv');
         $this->clearOldData($data);
         $this->saveNewData($data);
         $this->saveNewType($data);
    }

    // 清除旧数据
    public function clearOldData($data)
    {
        if (!empty($data)) {
            // 查找数据 删除链接
            $vodList = VodList::findOne(['list_name' => '电影']);
            if ($vodList) {
                $vod = Vod::find()->where(['vod_cid' => $vodList->list_id])->all();
                foreach ($vod as $val) {
                    $val->delete();
                    $this->stdout("删除成功" . PHP_EOL, Console::FG_GREEN, Console::UNDERLINE);
                }
            }
        }

        Yii::$app->db->createCommand('optimize table iptv_vod')->query();
        Yii::$app->db->createCommand('optimize table iptv_vodlink')->query();
    }

    // 新增类型
    public function saveNewType($data)
    {
        if ($data) {
            $type = array_unique(ArrayHelper::getColumn($data, 'group-title'));

            // 找到电影分类
            $vodList = VodList::findOne(['list_name' => '电影']);

            foreach ($type as $val) {
               $type = IptvType::find()->where(['field' => 'vod_type', 'vod_list_id' => $vodList->list_id])->one();

               if (is_null($type)) {
                    $type = new IptvType();
                    $type->field = 'vod_type';
                    $type->name = '类型';
                    $type->vod_list_id = $vodList->list_id;
                    $type->save(false);
               }

                $item = IptvTypeItem::find()->where(['name' => $val, 'type_id' => $type->id])->exists();
                if ($item == false) {
                    $item = new IptvTypeItem();
                    $item->name = $val;
                    $item->zh_name = BaiduTranslator::translate($val, 'en', 'zh');
                    $item->type_id = $type->id;
                    $item->sort = 0;
                    $item->save(false);
                    $this->stdout("新增{$val}" . PHP_EOL, Console::FG_GREEN, Console::UNDERLINE);
                }
            }
        }
    }

    //新增数据
    public function saveNewData($data)
    {
        // 找到电影分类
        $vodList = VodList::findOne(['list_name' => '电影']);
        if ($vodList) {
            if ($data) {
                foreach ($data as $val) {
                    if (Vod::find()->where(['vod_name' => $val['tvg-name']])->exists() == false) {
                        $vod = new Vod();
                        $vod->vod_cid = $vodList->list_id;
                        $vod->vod_name = $val['tvg-name'];
                        $vod->vod_type = $val['group-title'];
                        $vod->vod_keywords = $val['group-title'];
                        $vod->vod_pic = $val['tvg-logo'];

                        if ($vod->save(false)) {
                            $link = new Vodlink();
                            $link->url = $val['ts'];
                            $vod->link('vodLinks', $link);
                        }
                        $this->stdout("新增{$val['tvg-name']}" . PHP_EOL, Console::FG_GREEN, Console::UNDERLINE);
                    } else {
                        $this->stdout("存在{$val['tvg-name']}" . PHP_EOL, Console::FG_RED, Console::UNDERLINE);
                    }
                }
            }
        } else {
            echo "请在点播下新增电影分类";
        }

    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    public function download()
    {
        // 初始化数据
        $url = 'http://www.hdboxtv.net:8000/get.php?username=287994000050&password=287994000050&type=m3u_plus&output=ts';
        $data = file_get_contents($url);

        if (empty($data)) {
            throw new \Exception('下载失败');
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @param string $type 'ott|iptv'
     * @return array
     */
    public function initData($data, $type = "ott")
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

    private static function get($data)
    {
        if (isset($data[0]) && !empty($data[0])) {
            return trim($data[0]);
        }
        return null;
    }
}