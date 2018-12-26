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
use Symfony\Component\DomCrawler\Crawler;

/**
 * 豆瓣电影资料采集器
 * Class Douban
 * @package console\collectors\profile
 */
class Douban extends searcher implements searchByName
{

    //https://movie.douban.com/subject/30192043/?from=showing
    protected static $url = 'https://movie.douban.com/subject/{ID}/?from=showing';

    public function setSupportedLanguage()
    {
        return $this->supportedLanguages = 'zh-CN';
    }

    public static function getProxy()
    {
        return "http://122.243.15.27:9000";
    }

    private function getOptions()
    {
        return $options = 0 ? ['proxy' =>  self::getProxy()] : [];
    }

    public static function searchByName($name, $options)
    {
         $url = static::getQueryUrl($name);

         //$url = 'https://movie.douban.com/subject/2124724/?from=showing';

        if ($url) {
            $common = new common();
            /* @var $dom Crawler */
            $dom = $common->getDom($url, self::getOptions());
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

        return false;
    }

    // 豆瓣搜索建议
    private static function getQueryUrl($name)
    {
        $suggestUrl = "https://movie.douban.com/j/subject_suggest?q={QUERY_NAME}";
        $suggestUrl = str_replace('{QUERY_NAME}', urlencode($name), $suggestUrl);

        $common = new common();
        $client = $common->getHttpClient();

        $response = $client->request('GET',$suggestUrl,self::getOptions())->getBody()->getContents();

        if ($response) {
            $suggests = json_decode($response, true);
            if (!empty($suggests) && !empty($suggests[0]['id'])) {
                sleep(mt_rand(5,12));
                return str_replace('{ID}',$suggests[0]['id'], self::$url);
            }
        }

        return false;
    }

}