<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/6
 * Time: 10:37
 */

namespace console\collectors\profile;

use console\collectors\profile\interfaces\searchByName;
use yii\helpers\ArrayHelper;

/**
 * API文档地址：https://developers.themoviedb.org/3/getting-started/languages
 * 查询影片资料
 * Class profile
 * @package console\models
 */
class MOVIEDB extends searcher implements searchByName
{
    private static $api_key = '64c1188ae2bd62ac5620b429037adaf8';
    private static $url;

    public function setSupportedLanguage()
    {
        $this->supportedLanguages = ['en-US','zh-CN','vi-VN'];
    }

    /**
     * @param $name
     * @param $options array
     * @return bool|array
     */
    public static function searchByName($name, $options)
    {
        $lang = $options['language']??'en-US';
        return self::getBaseInfo($name, $lang);
    }

    public static function likeSearch($name, $lang = 'en-US')
    {
        $name = self::like($name, $lang);
        if ($name) {
            return self::searchByName($name, $lang);
        }

        return false;
    }

    private static function getBaseInfo($name, $lang = 'en-US')
    {
        $query = trim(urlencode($name));
        $api_key = self::$api_key;
        self::$url = "https://api.themoviedb.org/3/search/movie?api_key={$api_key}&language={$lang}&query={$query}&page=1&include_adult=false";

        try {
            $data = file_get_contents(self::$url);

            if (!empty($data)) {
                $data = json_decode($data, true);

                if (isset($data['results']) && !empty($data['results'])) {

                    $data = current($data['results']);

                    $profile = [];
                    if (isset($data['original_title'])) $profile['vod_title'] = $data['original_title'];
                    if (isset($data['original_title'])) $profile['vod_ename'] = $data['original_title'];
                    if (isset($data['vote_average'])) $profile['vod_gold'] = $data['vote_average'];
                    if (isset($data['vote_average'])) $profile['vod_douban_score'] = $data['vote_average'];
                    if (isset($data['poster_path'])) $profile['vod_pic'] = "https://image.tmdb.org/t/p/w370_and_h556_bestv2/" . $data['poster_path'];
                    if (isset($data['backdrop_path'])) $profile['vod_pic_bg'] = "https://image.tmdb.org/t/p/w370_and_h556_bestv2/" . $data['backdrop_path'];
                    if (isset($data['release_date'])) $profile['vod_scenario'] = $data['overview'];
                    if (isset($data['overview'])) $profile['vod_content'] = $data['overview'];
                    if (isset($data['overview'])) $profile['vod_language'] = $data['original_language'];
                    if (isset($data['release_date'])) $profile['vod_year'] = substr($data['release_date'], 0 ,4);
                    if (isset($data['release_date'])) $profile['vod_filmtime'] = $data['release_date'];
                    if (isset($data['imdb_id'])) $profile['vod_imdb_id'] = $data['imdb_id'];

                    $profile['vod_total'] = 1;

                    // 获取演员
                    $actorInfo = self::getActorInfo($data['id']);

                    if ($actorInfo) {
                        $profile['vod_actor'] = $actorInfo['vod_actor'];
                        $profile['vod_director'] = $actorInfo['vod_director'];
                    }

                    sleep(1);

                    // 获取语言 地区 分类
                    $detailInfo = self::getDetailInfo($data['id'], $lang);

                    if ($detailInfo) {
                        if (isset($detailInfo['vod_language'])) $profile['vod_language'] = $detailInfo['vod_language'];
                        if (isset($detailInfo['vod_area'])) $profile['vod_area'] = $detailInfo['vod_area'];
                        if (isset($detailInfo['vod_type'])) $profile['vod_type'] = $detailInfo['vod_type'];
                        if (isset($detailInfo['vod_keyword'])) $profile['vod_keyword'] = $detailInfo['vod_keyword'];
                    }

                    return $profile;
                }
            }

                echo "the movie db 接口返回数据异常/没有返回" . PHP_EOL;
                return false;

        } catch (\Exception $e) {
            echo $e->getMessage();
            echo "the movie db接口访问失败" . PHP_EOL;
            return false;
        }
    }

    private static function getDetailInfo($movie_id, $lang = 'en-US')
    {
        $api_key = self::$api_key;
        self::$url = "https://api.themoviedb.org/3/movie/{$movie_id}?api_key={$api_key}&language={$lang}";

        try {
            $data = file_get_contents(self::$url);

            if (empty($data)) return false;

            $response = [];
            $data = json_decode($data, true);

            if ($data) {
                if (isset($data['spoken_languages'][0]['iso_639_1'])) {
                    $response['vod_language'] = $data['spoken_languages'][0]['iso_639_1'];
                }
                if (isset($data['production_countries'][0]['iso_3166_1'])) {
                    $response['vod_area'] = $data['production_countries'][0]['name'];
                }
                if (isset($data['genres'])) {
                    $response['vod_keyword'] = $response['vod_type'] = implode(',', ArrayHelper::getColumn($data['genres'], 'name'));
                }
            }

            return $response;

        }catch (\Exception $e) {

        }

        return false;
    }

    private static function getActorInfo($movieID)
    {
        $api_key = self::$api_key;
        self::$url = "https://api.themoviedb.org/3/movie/{$movieID}/credits?api_key={$api_key}";

        try {
            $data = file_get_contents(self::$url);

            if (empty($data)) {
                return false;
            }

            $response = [];
            $data = json_decode($data, true);

            // 导演数据
            if (isset($data['crew'])) {
                $director = [];
                foreach ($data['crew'] as $crew) {
                    if ($crew['job'] == 'Director') {
                        array_push($director, $crew['name']);
                    }
                }
                if (!empty($director)) {
                    $response['vod_director'] = implode(',', $director);
                }
            }
            // 演员数据
            if (isset($data['cast'])) {
                $actor = [];
                foreach ($data['cast'] as $cast) {
                    if (isset($cast['name'])) {
                        array_push($actor, $cast['name']);
                    }
                }
                if (!empty($actor)) {
                    $response['vod_actor'] = implode(',', $actor);
                }
            }

            return $response;

        } catch (\Exception $e) {

        }

    }

    private static function like($name, $lang = 'en-US')
    {
        $url = 'https://www.themoviedb.org/search/multi?language=en-US&query=' . urlencode($name);
        $data = file_get_contents($url);
        if (empty($data)) {
            return false;
        }

        $data = json_decode($data, true);
        if (!empty($data['results']) && isset($data['results'][0]['title'])) {
            return $data['results'][0]['title'];
        }

        return false;
    }



}