<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/6
 * Time: 10:37
 */

namespace console\models;

/**
 * 爬取影片数据
 * Class profile
 * @package console\models
 */
class profile
{
    private static $api_key = '64c1188ae2bd62ac5620b429037adaf8';
    private static $url;

    static private function setUrl($query, $lang="en-US")
    {
        $query = urlencode($query);
        $api_key = self::$api_key;
        self::$url = "https://api.themoviedb.org/3/search/movie?api_key={$api_key}&language={$lang}&query={$query}&page=1&include_adult=false";
    }

    /**
     * @param $name
     * @param string $lang
     * @return bool|array
     */
    static public function search($name, $lang='en-US')
    {
        self::setUrl($name, $lang);
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
                    $profile['vod_pic'] = "https://image.tmdb.org/t/p/w370_and_h556_bestv2/" . $data['poster_path'];
                    $profile['vod_pic_bg'] = "https://image.tmdb.org/t/p/w370_and_h556_bestv2/" . $data['backdrop_path'];
                    $profile['vod_scenario'] = $data['overview'];
                    $profile['vod_content'] = $data['overview'];
                    $profile['vod_language'] = $data['original_language'];
                    $profile['vod_year'] = substr($data['release_date'], 0 ,4);
                    $profile['vod_filmtime'] = $data['release_date'];

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


}