<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/12
 * Time: 17:55
 */

namespace console\collectors\profile;


use common\components\Func;
use console\collectors\common;
use console\collectors\profile\interfaces\searchByName;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Yii;
/**
 * 豆瓣电影资料采集器 https://movie.douban.com/subject/30192043/?from=showing
 * Class Douban
 * @package console\collectors\profile
 */
class Douban extends searcher implements searchByName
{

    protected static $url = 'https://movie.douban.com/subject/{ID}/?from=showing';
    protected static $currentProxy = '';
    protected static $proxyKey = 'kuai11';
    protected static $initialProxyNumber = 2;
    protected static $timeout = 12;
    public static $isUseProxy = false;
    public static $runTimeLog = '/tmp/douban-proxy.log';
    public static $whenForbiddenSleepTime = 3700;

    public function setSupportedLanguage()
    {
        return $this->supportedLanguages = 'zh-CN';
    }

    public static function loadProxy()
    {
        $proxy_list = Yii::$app->cache->getOrSet(self::$proxyKey, function() {
            return json_encode([]);
        });

        return json_decode($proxy_list, true);
    }

    private static function setProxyAttr($proxy,$key)
    {
        $proxy_list = self::loadProxy();
        if (isset($proxy_list[$proxy][$key])) {
            $proxy_list[$proxy][$key]++;
            isset($proxy_list[$proxy]['error']) ? $proxy_list[$proxy]['error']++ : $proxy_list[$proxy]['error'] = 1;
        } else {
            $proxy_list[$proxy][$key] = 1;
            isset($proxy_list[$proxy]['error']) ? $proxy_list[$proxy]['error']++ : $proxy_list[$proxy]['error'] = 1;
        }

        self::saveProxy($proxy_list);
    }

    protected static function saveProxy($proxy_list)
    {
        Yii::$app->cache->set(self::$proxyKey, json_encode($proxy_list));
    }

    private static function timeoutProxyIncrement($ip)
    {
        self::setProxyAttr($ip,'timeout');
    }

    private static function refusedIncrement($ip)
    {
        self::setProxyAttr($ip, 'refused');
    }

    private static function forbiddenIncrement($ip)
    {
        self::setProxyAttr($ip,'forbidden');
    }

    private static function otherErrorIncrement($ip)
    {
        self::setProxyAttr($ip,'other');
    }

    protected static function getProxyFromApi($num = 2)
    {

        $client = new Client();
        $proxies =  $client->get("http://dps.kdlapi.com/api/getdps/?orderid=994596123406690&num={$num}&pt=1&dedup=1&format=json&sep=1")
                        ->getBody()
                        ->getContents();

        $proxies = json_decode($proxies, true);
        return $proxies['data']['proxy_list']??false;
    }

    protected static function addNewProxy($num)
    {
        echo "添加新的代理{$num}" . PHP_EOL;

        if ($num <= 0) return false;
        //$proxy_list = static::getProxyFromApi($num);
        $proxy_list = false;
        $proxies = [];

        if ($proxy_list) {
            foreach ($proxy_list as $key => $proxy) {
                $proxies[$proxy] = [
                    'error' => 0,
                    'refused' => 0,
                    'timeout' => 0
                ];
            }
        }

        return $proxies;
    }

    protected static function getProxy()
    {
        $proxies = self::loadProxy();
        if (empty($proxies)) {
           $proxies = static::addNewProxy(self::$initialProxyNumber);
        } else {
            // 剔除错误数大于10的代理
            $needToAdd = 0;

            foreach ($proxies as $key => $cal) {
                if (    isset($cal['error']) && $cal['error'] >= 12 ||
                        isset($cal['forbidden']) && $cal['forbidden'] >= 2 ||
                        isset($cal['timeout']) && $cal['timeout'] >= 4 ||
                        isset($cal['refused']) && $cal['refused'] >= 3 ) {

                    echo "移除代理" . $key . PHP_EOL;
                    unset($proxies[$key]);
                    if (count($proxies) < self::$initialProxyNumber) {
                        $needToAdd++;
                    }
                }
            }

            if ($needToAdd) {
                $addProxy = static::addNewProxy($needToAdd);
                $proxies = array_merge($proxies, $addProxy);
            }
        }

        echo "当前代理池有" . count($proxies) . '个 ';


        self::saveProxy($proxies);

        $proxies = array_keys($proxies);

        return $proxies[mt_rand(0, count($proxies) - 1)];
    }

    private static function getOptions()
    {
        if (self::$isUseProxy) {
            self::$currentProxy = self::getProxy();
            echo "使用代理" . self::$currentProxy . PHP_EOL;

            return [
                'proxy' =>  self::$currentProxy,
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
                'connect_timeout' => self::$timeout,
            ];
        } else {
            return [
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
                'connect_timeout' => self::$timeout,
            ];
        }

    }

    public function searchByNameViaProxy($name, $options)
    {
        self::$isUseProxy = true;
        touch(self::$runTimeLog);
        file_put_contents(self::$runTimeLog,time());

        return self::searchByName($name, $options);
    }

    protected static function whenTaskReceive403(\Exception $e)
    {
        if (strpos($e->getMessage(), '403')) {
            if (self::$isUseProxy == false) {

                for($i=1; $i<=self::$whenForbiddenSleepTime; $i++) {
                    echo "喜提403,休息一下 剩余" . (self::$whenForbiddenSleepTime - $i) . "秒"  . PHP_EOL;
                    sleep(1);
                }
            }
        }
    }

    public static function searchByName($name, $options)
    {
         $clientOptions = self::getOptions();
         $url = static::getQueryUrl($name, $clientOptions);

         if ($url) {
            $common = new common();
            /* @var $dom Crawler */

            try {
                $dom = $common->getDom($url, $clientOptions);
            } catch (\Exception $e) {
                self::whenTaskReceive403($e);
                self::dealProxyError($e);
                return false;
            }

            $doubanProfile = [];

            $content= $dom->filter('#content');
            if ($dom->count() && $content->filter('#info')->count()) {
                $info = $content->filter('#info')->html();
                $info = explode('<br>',$info);

                foreach ($info as $text) {
                   $text = trim(strip_tags($text));
                   if (strpos($text, ':') !== false) {
                       list($type, $value) = explode(':', $text);

                       $value = preg_replace('/\s/', '', $value);

                       switch ($type)
                       {
                           case '导演':
                               $doubanProfile['director'] = $value;
                               break;
                           case '编剧':
                               $doubanProfile['screen_writer'] = $value;
                               break;
                           case '主演':
                               $doubanProfile['actor'] = $value;
                               break;
                           case '类型':
                               $doubanProfile['type'] = $value;
                               break;
                           case '制片国家/地区':
                               $doubanProfile['area'] = $value;
                               break;
                           case '语言':
                               $doubanProfile['language'] = $value;
                               break;
                           case '上映日期':
                               $doubanProfile['year'] = substr($value,0,4);
                               $doubanProfile['date'] = substr($value,0, 10);
                               break;
                           case '片长':
                               if ($value = Func::pregSieze('/\d+/', $value)) {
                                   $hour = ceil($value / 60 ) == '0' ? '00' : sprintf('%02d',ceil($value / 60 ) );
                                   $min  = sprintf('%02d', $value % 60);

                                   $doubanProfile['length'] = $hour . ':' . $min;
                               }
                               break;
                           case '又名':
                               $doubanProfile['alias_name'] = $value;
                               break;
                           case 'IMDb链接':
                               $doubanProfile['imdb_id'] = $value;
                               break;
                       }
                   }
                }

                if ($dom->filter('#mainpic img')->count()) {
                    $doubanProfile['cover'] = $dom->filter('#mainpic img')->first()->attr('src');
                }

                if ($dom->filterXPath("//a[@data-object_id]")->count()) {
                    $doubanProfile['douban_id'] = $dom->filterXPath("//a[@data-object_id]")->first()->attr('data-object_id');
                }

                if ($dom->filter('.rating_self strong')->count()) {
                    $doubanProfile['douban_score'] = $dom->filter('.rating_self strong')->text();
                }

                if ($dom->filter('.rating_people span')->count()) {
                    $doubanProfile['douban_voters'] = $dom->filter('.rating_people span')->text();
                }

                if ($dom->filter('.tags-body a')->count()) {
                    $userTags = [];
                    $doubanProfile['user_tag'] = $dom->filter('.tags-body a')->each(function(Crawler $tag) use (&$userTags) {
                        $userTags[] = $tag->text();
                    });

                    $doubanProfile['user_tag'] = implode(',', $userTags);
                }

                if ($dom->filter('.related-info .indent')->count()) {
                    $plot = $dom->filter('.related-info .indent')->first()->text();
                    $plot = preg_replace('/\s/','', $plot);
                    $plot = preg_replace('/©豆瓣/','', $plot);
                    $doubanProfile['plot'] = $plot;
                }

                if ($dom->filterXPath("//span[@class='short']")->count()) {
                    $shortComment = [];
                    $dom->filterXPath("//span[@class='short']")->each(function (Crawler $span) use (&$shortComment) {
                        $shortComment[] = $span->text();
                    });
                    if (count($shortComment) > 5) {
                       $shortComment = array_slice($shortComment, 0, 5);
                    }

                    $doubanProfile['comment'] = json_encode($shortComment);
                }

                $doubanProfile['name'] = $name;

                return $doubanProfile;
            }
        }

        return null;
    }

    protected static function dealProxyError(\Exception $error)
    {
        echo "Message：" . $error->getMessage() . PHP_EOL;

        if (strpos($error, 'Connection refused') !== false) {
            static::refusedIncrement(self::$currentProxy);
        } else if (strpos($error, 'Connection timed out')) {
            static::timeoutProxyIncrement(self::$currentProxy);
        } else if (strpos($error, '403')) {
            static::forbiddenIncrement(self::$currentProxy);
        }else {
            static::otherErrorIncrement(self::$currentProxy);
        }
    }

    // 豆瓣搜索建议
    private static function getQueryUrl($name,$options)
    {
        $suggestUrl = "https://movie.douban.com/j/subject_suggest?q={QUERY_NAME}";
        $suggestUrl = str_replace('{QUERY_NAME}', urlencode($name), $suggestUrl);

        $common = new common();
        $client = $common->getHttpClient();

        try {
            $response = $client->request('GET',$suggestUrl,$options)->getBody()->getContents();
            self::$isUseProxy ? sleep(1) : sleep(2);
        } catch (\Exception $e) {
            self::whenTaskReceive403($e);
            self::dealProxyError($e);
            return false;
        }

        if ($response) {
            $suggests = json_decode($response, true);
            if (!empty($suggests) && !empty($suggests[0]['id'])) {
                //sleep(1);
                return str_replace('{ID}',$suggests[0]['id'], self::$url);
            }
        }

        return false;
    }

}