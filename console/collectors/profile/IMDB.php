<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/25
 * Time: 15:14
 */

namespace console\collectors\profile;

use console\collectors\profile\interfaces\searchById;
use console\collectors\profile\interfaces\searchByName;

class IMDB implements searchByName,searchById
{
    public $result = ['status'=>'false','message'=>'Unknown error'];

    private static $api_key = 'gPK2rEcengGUwl80cN6sTZoqAX1IaV';
    private static $url = 'http://imdbapi.net/api';

    public static function searchByName($title, $otherField = null)
    {
        $data = self::title($title, 'json');
        if ($data) {
            $data = json_decode($data, true);
            if ($data && isset($data['status']) && $data['status'] == true) {
                $profile = [];
                if (!empty($data['title'])) $profile['vod_title'] = $data['title'];
                if (!empty($data['title'])) $profile['vod_ename'] = $data['title'];
                if (!empty($data['year'])) $profile['vod_year'] = $data['year'];
                if (!empty($data['director'])) $profile['vod_director'] = $data['director'];
                if (!empty($data['actors'])) $profile['vod_actor'] = str_replace(' ', '', $data['actors']);
                if (!empty($data['plot'])) $profile['vod_scenario'] = $data['plot'];
                if (!empty($data['plot'])) $profile['vod_content'] = $data['plot'];
                if (!empty($data['language'])) $profile['vod_language'] = $data['language'];
                if (!empty($data['year'])) $profile['vod_year'] = $data['year'];
                if (!empty($data['released'])) $profile['vod_filmtime'] = date('Y-m-d', strtotime(substr($data['released'],0 , strpos($data['released'], '(') )));
                if (!empty($data['genre'])) $profile['vod_type'] = str_replace(' ', '', $data['genre']);
                if (!empty($data['genre'])) $profile['vod_keyword'] = str_replace(' ', '', $data['genre']);
                if (!empty($data['poster'])) $profile['vod_pic'] = $data['poster'];
                if (!empty($data['country'])) $profile['vod_area'] = $data['country'];
                if (!empty($data['runtime'])) $profile['vod_length'] = $data['runtime'];
                if (empty($profile)) {
                    return false;
                }

                return $profile;
            }

        }

        return false;
    }

    /**
     * 按 id 进行搜索
     * @param bool $id
     * @param string $type
     * @return mixed
     */
    public static function searchById($id = false,$type = 'json')
    {
        $param = array(
            'key' => self::$api_key,
            'id' => $id,
            'type' => $type
        );

        $result = self::_curl($param);

        return $result;
    }

    /**
     * 按名字进行搜索
     * @param bool $title
     * @param string $type
     * @return mixed
     */
    private static function title($title = false,$type = 'json')
    {
        $param = array(
            'key' => self::$api_key,
            'title'=>$title,
            'type'=>$type
        );

        $result = self::_curl($param);

        return $result;
    }

    /**
     * 搜索关键字 年份
     * @param string $keyword
     * @param string $year
     * @param int $page
     * @param string $type
     * @return mixed
     */
    public static function keyword($keyword = '', $year = '',$page = 0,$type = 'json')
    {
        $param = array(
            'key'=> self::$api_key,
            'id' => $keyword,
            'year' => $year,
            'page' => $page,
            'type' => $type
        );

        $result = self::_curl($param);

        return $result;
    }

    private static function _curl($param)
    {
        $ch = curl_init(self::$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($param));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST  , 2);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}