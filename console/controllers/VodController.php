<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/7
 * Time: 13:22
 */

namespace console\controllers;

use backend\models\PlayGroup;
use backend\models\VodProfiles;
use common\components\Func;
use common\models\Vodlink;
use console\collectors\profile\Douban;
use GuzzleHttp\Client;
use Yii;
use common\models\Vod;
use console\models\Tv;
use console\traits\Similar;
use console\collectors\local\VodCollector;
use console\models\Cartoon;
use console\models\Movie;
use console\models\Variety;
use yii\console\Controller;
use yii\helpers\Console;

class VodController extends Controller
{
    use Similar;

    // 清除旧数据
    public function actionClearData()
    {
        $this->stdout("清除旧数据" . PHP_EOL, Console::FG_GREEN);
        Yii::$app->db->createCommand('truncate table iptv_vod')->query();
        Yii::$app->db->createCommand('truncate table iptv_play_group')->query();
        Yii::$app->db->createCommand('truncate table iptv_vodlink')->query();
        $this->stdout("数据清除完毕" . PHP_EOL, Console::FG_GREEN);
    }

    public function actionDisk($type="movie")
    {
        switch ($type)
        {
            case 'movie':
                $vodCollector = $this->movie();
                break;
            case 'cartoon':
                $vodCollector = $this->cartoon();
                break;
            case 'variety':
                $vodCollector = $this->variety();
                break;
            case 'kr':
                $vodCollector = $this->kr();
                break;
            case 'hk':
                $vodCollector = $this->hk();
                break;
            case 'local':
                $vodCollector = $this->local();
                break;
            case 'us':
                $vodCollector = $this->us();
                break;
            default;
                Console::error("不支持{$type}");
                return false;
        }

        return $vodCollector->doCollect();
    }

    public function actionDiskAll()
    {
        foreach (['movie','cartoon','variety','kr','hk','local','us'] as $item){
            $this->actionDisk($item);
        }
    }

    private function movie()
    {
        return $vodCollector = new VodCollector(new Movie(),[
            'dir'      => '/home/newpo/pinyin/movie/',
            'playpath' => '/vod/movie',
            'type'     => 'movie',
            'language' => '中文',
            'area'     => '中国'
        ]);
    }

    private function cartoon()
    {
        return $vodCollector = new VodCollector(new Cartoon(),[
            'dir'      => '/home/newpo/pinyin/dongman/',
            'playpath' => '/vod/dongman',
            'type'     => 'cartoon',
            'language' => '中文',
            'area'     => '中国'
        ]);
    }

    private function variety()
    {
        return $vodCollector = new VodCollector(new Variety(),[
            'dir'      => '/home/newpo/pinyin/zongyi/',
            'playpath' => '/vod/zongyi',
            'type'     => 'variety',
            'language' => '中文',
            'area'     => '中国'
        ]);
    }

    private function kr()
    {
        return $vodCollector = new VodCollector(new Tv(),[
            'dir'      => '/home/newpo/pinyin/hanju/',
            'playpath' => '/vod/hanju',
            'type'     => 'serial',
            'language' => '韩语',
            'area'     => '韩国',
            'genre'    => '韩剧'
        ]);
    }

    private function local()
    {
        return $vodCollector = new VodCollector(new Tv(),[
            'dir'      => '/home/newpo/pinyin/neidi/',
            'playpath' => '/vod/neidi',
            'type'     => 'serial',
            'language' => '中文',
            'area'     => '中国内地',
            'genre'    => '内地'
        ]);
    }

    private function hk()
    {
        return $vodCollector = new VodCollector(new Tv(),[
            'dir'      => '/home/newpo/pinyin/gangju/',
            'playpath' => '/vod/gangju',
            'type'     => 'serial',
            'language' => '中文',
            'area'     => '中国香港',
            'genre'    => '港剧'
        ]);
    }

    private function us()
    {
        return $vodCollector = new VodCollector(new Tv(),[
            'dir'      => '/home/newpo/pinyin/meiju/',
            'playpath' => '/vod/meiju',
            'type'     => 'serial',
            'language' => '英语',
            'area'     => '美国',
            'genre'    => '美剧'
        ]);
    }

    /**
     * 专题归类
     */
    public function actionLike()
    {
       $vods = Vod::find()->all();
       foreach ($vods as $vod) {
           $this->stdout("检测{$vod->vod_name}" . PHP_EOL);
           foreach ($vods as $_vod) {
               if ($vod->vod_id != $_vod->vod_id) {
                   $similarValue = ceil($this->getSimilar($vod->vod_name, $_vod->vod_name) * 10);
                   if ($similarValue > 9) {
                        $vod->vod_series = $this->getLCS($vod->vod_name, $_vod->vod_name);
                        $vod->vod_series = trim(preg_replace('/\s*S\d+\s*/', '', $vod->vod_series));
                        $vod->vod_series = preg_replace('/\s(?=\s)/', "\\1", $vod->vod_series);

                        $vod->save(false);
                        $this->stdout("{$vod->vod_name} 设置系列为 :{$vod->vod_series}" . PHP_EOL ,Console::FG_BLUE);
                   }
               }

           }
       }
    }

    private function getWasteTime($time)
    {
        if ($time < 60) {
            return $time . ' s';
        } else if ($time > 60 && $time < 3600) {
            return ceil($time/60) . ' min ' . ($time  % 60 ) . ' s';
        } else {
            $hour = ceil($time/3600);
            $lastSecond =$time % 3600;
            return  $hour. ' h ' . ceil($lastSecond/60) . ' min ' . ($lastSecond % 60) . 's';
        }
    }

    /**
     * 清除数据
     */
    public function actionNoRepeat()
    {
        foreach (VodProfiles::find()->groupBy('name')->batch() as $key =>  $profiles) {
            $this->stdout($key * 100 .PHP_EOL);
            foreach ($profiles as $profile) {
                if ($profile->name && VodProfiles::find()->where(['name' => $profile->name])->count() >=2) {
                     // 删除其它
                     $this->stdout("删除 {$profile->name}" . PHP_EOL);
                     VodProfiles::deleteAll(['and', ['name' => $profile->name], ['!=', 'id', $profile->id]]);
                }
            }
        }

        echo VodProfiles::find()->count();
    }

    public function actionBaidu()
    {

        $taskOptions = [
            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28213&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E4%B8%AD%E5%9B%BD%E5%8A%A8%E6%BC%AB%20%E5%88%97%E8%A1%A8&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery1102024600666104391022_1545732725173&_=1545732725177',
                'cacheKey' => 'h14fdfhgpage',
                'title' => '中国动漫',
                'total' => 71,
                'country' => '中国',
                'type' => 'cartoon'
            ],
            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28213&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E6%97%A5%E6%9C%AC%E5%8A%A8%E6%BC%AB%20%E5%88%97%E8%A1%A8&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery1102024600666104391022_1545732725173&_=1545732725181',
                'cacheKey' => 'h161313gpaxge',
                'title' => '日本动漫',
                'total' => 110,
                'country' => '日本',
                'type' => 'cartoon'
            ],
            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28213&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E7%BE%8E%E5%9B%BD%E5%8A%A8%E6%BC%AB%20%E5%88%97%E8%A1%A8&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery1102024600666104391022_1545732725173&_=1545732725185',
                'cacheKey' => 'h18gpagawage',
                'title' => '美国动漫',
                'total' => 44,
                'country' => '美国',
                'type' => 'cartoon'
            ],
            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28213&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E8%8B%B1%E5%9B%BD%E5%8A%A8%E6%BC%AB%20%E5%88%97%E8%A1%A8&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery1102024600666104391022_1545732725173&_=1545732725188',
                'cacheKey' => 'h179asdasdgpa65ge',
                'title' => '英国动漫',
                'total' => 7,
                'country' => '英国',
                'type' => 'cartoon'
            ],
            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28213&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E6%B3%95%E5%9B%BD%E5%8A%A8%E6%BC%AB%20%E5%88%97%E8%A1%A8&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery1102024600666104391022_1545732725173&_=1545732725196',
                'cacheKey' => '54645A6ghtgth',
                'title' => '法国动漫',
                'total' => 7,
                'country' => '法国',
                'type' => 'cartoon'
            ],
            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28213&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E6%B3%95%E5%9B%BD%E5%8A%A8%E6%BC%AB%20%E5%88%97%E8%A1%A8&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery1102024600666104391022_1545732725173&_=1545732725196',
                'cacheKey' => 'asd3ASD546a',
                'title' => '越南电影',
                'total' => 6,
                'country' => '越南',
                'type' => 'movie'
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28213&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E9%9F%A9%E5%9B%BD%E5%8A%A8%E6%BC%AB&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293055',
                'cacheKey' => '67342335adafgh',
                'title' => '韩国动漫',
                'total' => 3,
                'country' => '韩国',
                'type' => 'cartoon'
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E4%B8%AD%E5%9B%BD%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293065',
                'cacheKey' => '6734GSE3fgh',
                'title' => '中国电视剧',
                'total' => 180,
                'country' => '中国',
                'type' => 'serial'
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E5%8F%B0%E6%B9%BE%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293078',
                'cacheKey' => '67ASD34fgh',
                'title' => '台湾电视剧',
                'total' => 20,
                'country' => '台湾',
                'type' => 'serial'
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E9%A6%99%E6%B8%AF%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293081',
                'cacheKey' => '6734fgh',
                'title' => '香港电视剧',
                'total' => 23,
                'country' => '香港',
                'type' => 'serial'
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E7%BE%8E%E5%9B%BD%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293089',
                'cacheKey' => '6734576HTfgh',
                'title' => '美国电视剧',
                'total' => 69,
                'country' => '美国',
                'type' => 'serial',
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E8%8B%B1%E5%9B%BD%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293092',
                'cacheKey' => '6734ADRGHfgh',
                'title' => '英国电视剧',
                'total' => 31,
                'country' => '英国',
                'type' => 'serial',
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E6%97%A5%E6%9C%AC%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293094',
                'cacheKey' => '6734fGEWTGgh',
                'title' => '日本电视剧',
                'total' => 61,
                'country' => '日本',
                'type' => 'serial',
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E9%9F%A9%E5%9B%BD%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293096',
                'cacheKey' => '6734fDGTRTHgh',
                'title' => '韩国电视剧',
                'total' => 24,
                'country' => '韩国',
                'type' => 'serial',
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E8%B6%8A%E5%8D%97%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293106',
                'cacheKey' => '6734fDGTRTHgh',
                'title' => '越南电视剧',
                'total' => 4,
                'country' => '越南',
                'type' => 'serial',
            ],

            [
                'url' => 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=28287&from_mid=1&&format=json&ie=utf-8&oe=utf-8&query=%E6%B3%B0%E5%9B%BD%E7%94%B5%E8%A7%86%E5%89%A7&sort_key=16&sort_type=1&stat0=&stat1=&stat2=&stat3=&pn={PAGE}&rn=100&cb=jQuery110206994629836097317_1545733293053&_=1545733293111',
                'cacheKey' => '6734fDGTRTHgh',
                'title' => '泰国电视剧',
                'total' => 15,
                'country' => '泰国',
                'type' => 'serial',
            ],
        ];

        $start = time();

        foreach ($taskOptions as $taskOption) {
            $this->baiduList($taskOption,$start);
        }

    }

    private function baiduList($taskOptions,$start)
    {
        $url = $taskOptions['url'];
        $client  = new Client();

        for ($page = 1; $page<=$taskOptions['total']; $page++) {
            $alreadyPage = Yii::$app->cache->getOrSet($taskOptions['cacheKey'], function() use ($page) {
                return $page;
            });

            if ($page < $alreadyPage)  continue;

            $this->taskPrint("采集百度{$taskOptions['country']}列表:第{$page}页" . PHP_EOL);

            $requestUrl = str_replace('{PAGE}', $page * 100, $url);

            $options = [
                'headers' => [
                    'User-Agent'      => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
                    'Referer'         =>  'https://www.baidu.com/s?wd=%E7%BE%8E%E5%9B%BD%E7%94%B5%E5%BD%B1&rsv_spt=1&rsv_iqid=0xac309afd0000075d&issp=1&f=3&rsv_bp=0&rsv_idx=2&ie=utf-8&rqlang=&tn=baiduhome_pg&ch=&rsv_enter=1&inputT=8018',
                    'Accept-Language' => 'zh-CN,en;q=0.5'
                ],
            ];

            $result = $client->request('GET', $requestUrl, $options);
            $data = $result->getBody()->getContents();
            $data = Func::pregSieze('/{.*}/',$data);
            $data = json_decode($data,true);
            if (isset($data['data'][0]['result']) && !empty($data['data'][0]['result'])) {
                foreach ($data['data'][0]['result'] as $movie) {

                    if ($movie['name'] && VodProfiles::find()->where(['name' => $movie['name'],'area' => $taskOptions['country'],'media_type' => $taskOptions['type']])->exists() == false) {
                        $profiles = new VodProfiles();
                        $profiles->name = $movie['name'];
                        $profiles->area = $taskOptions['country'];
                        $profiles->cover = $movie['kg_pic_url']??'';
                        $profiles->media_type = $taskOptions['type'];
                        $profiles->douban_score = Func::pregSieze('/\d\.\d/', $movie['additional'])??'';

                        $profiles->save(false);
                        $this->stdout("{$profiles->name} 新增".PHP_EOL, Console::FG_GREEN);
                    } else {
                        $this->stdout("{$movie['name'] } 存在".PHP_EOL,Console::FG_RED);
                    }
                }

                Yii::$app->cache->set($taskOptions['cacheKey'], $page);
                $this->sleepPrint(6,12);
                $time = $this->getWasteTime(time() - $start);
                $this->stdout(" 任务已运行:{$time}".PHP_EOL );
            }
        }
    }

    public function taskPrint($taskName)
    {
        $this->stdout(date('Y-m-d H:i:s  ') . $taskName, Console::FG_CYAN);
    }

    public function actionDouban()
    {
        $start = time();

        foreach (VodProfiles::find()->where(['douban_search' => 0])->andWhere(['<=', 'id', '45000'])->batch(1) as $profiles) {
           foreach ($profiles as $profile) {
              $this->taskPrint("采集豆瓣数据:{$profile->name}");

              try {
                  $doubanProfile =  Douban::searchByName($profile->name, ['language' => 'zh-CN']);
              } catch (\Exception $e) {
                  echo $e->getCode().PHP_EOL;
                  echo $e->getMessage() .PHP_EOL;


                  continue;
              }


              if ($doubanProfile) {
                foreach ($doubanProfile as $field => $value) {
                    $profile->$field = $value;
                }

                $profile->douban_search = 1;

                /* @var $profile VodProfiles*/
                $profile->save(false);
                $this->stdout("√" , Console::FG_GREEN);

                if ($profile->hasErrors()) {
                    print_r($profile->getFirstErrors());
                    $this->stdout("Save:X" , Console::FG_RED);
                }

               } else {
                  VodProfiles::updateAll(['douban_search' => 1], ['name' => $profile->name]);
                  $this->stdout("X"  , Console::FG_RED);
               }

               $this->sleepPrint(1,2);

               $time = $this->getWasteTime(time() - $start);
               $this->stdout(" 任务已运行:{$time}".PHP_EOL );
           }
       }
    }

    public function sleepPrint($min,$max)
    {
        $time = mt_rand($min, $max);
        while ($time > 0) {
            $this->stdout('.', Console::FG_GREY);
            $time --;
            sleep(1);
        }
    }

    public function actionTruncate()
    {
        Yii::$app->db->createCommand('truncate ' . Vod::tableName())->execute();
        Yii::$app->db->createCommand('truncate ' . Vodlink::tableName())->execute();
        Yii::$app->db->createCommand('truncate ' . PlayGroup::tableName())->execute();
    }

    public function actionProxy()
    {
        $proxies = Yii::$app->cache->getOrSet('proxies1', function () {
           $client = new Client();
           return $client->get('http://dps.kdlapi.com/api/getdps/?orderid=984589736126781&num=5&pt=1&ut=2&dedup=1&format=json&sep=1')
                ->getBody()
                ->getContents();
        });

        print_r($proxies);
    }

}