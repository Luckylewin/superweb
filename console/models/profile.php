<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/6
 * Time: 10:37
 */

namespace console\models;
use yii\helpers\ArrayHelper;

/**
 * 爬取影片数据
 * Class profile
 * @package console\models
 */
class profile
{
    private static $api_key = '64c1188ae2bd62ac5620b429037adaf8';
    private static $url;


    static public function getActorInfo($movieID, $lang = 'en-US')
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

        }catch (\Exception $e) {

        }

    }

    static public function getBaseInfo($name, $lang = 'en-US')
    {
        $query = urlencode($name);
        $api_key = self::$api_key;
        self::$url = "https://api.themoviedb.org/3/search/movie?api_key={$api_key}&language={$lang}&query={$query}&page=1&include_adult=false";

        try {
            $data = file_get_contents(self::$url);
            if (!empty($data)) {
                $data = json_decode($data, true);
                if (isset($data['results']) && !empty($data['results'])) {
                    $data = current($data['results']);

                    $profile = [];
                    $profile['vod_title'] = $data['original_title'];
                    $profile['vod_ename'] = $data['original_title'];
                    $profile['vod_gold'] = $data['vote_average'];
                    $profile['vod_douban_score'] = $data['vote_average'];
                    $profile['vod_pic'] = "https://image.tmdb.org/t/p/w370_and_h556_bestv2/" . $data['poster_path'];
                    $profile['vod_pic_bg'] = "https://image.tmdb.org/t/p/w370_and_h556_bestv2/" . $data['backdrop_path'];
                    $profile['vod_scenario'] = $data['overview'];
                    $profile['vod_content'] = $data['overview'];
                    $profile['vod_language'] = $data['original_language'];
                    $profile['vod_year'] = substr($data['release_date'], 0 ,4);
                    $profile['vod_filmtime'] = $data['release_date'];

                    // 获取演员
                    $actorInfo = self::getActorInfo($data['id']);

                    if ($actorInfo) {
                        $profile['vod_actor'] = $actorInfo['vod_actor'];
                        $profile['vod_director'] = $actorInfo['vod_director'];
                    }

                    // 获取语言 地区 分类
                    $detailInfo = self::getDetailInfo($data['id'], $lang);
                    if ($detailInfo) {
                        $profile['vod_language'] = $detailInfo['vod_language'];
                        $profile['vod_area'] = $detailInfo['vod_area'];
                        $profile['vod_type'] = $detailInfo['vod_type'];
                        $profile['vod_keyword'] = $detailInfo['vod_keyword'];
                    }

                    return $profile;
                }
            } else {
                echo "接口访问失败";
                return false;
            }
        } catch (\Exception $e) {
            echo "接口访问失败";
            return false;
        }
    }

    static public function getDetailInfo($movie_id, $lang = 'en-US')
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
    }

    /**
     * @param $name
     * @param string $lang
     * @return bool|array
     */
    static public function search($name, $lang='en-US')
    {
        return self::getBaseInfo($name, $lang);
    }

}