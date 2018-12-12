<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/26
 * Time: 10:46
 */

namespace console\collectors\profile;

// http://www.omdbapi.com
use console\collectors\profile\interfaces\searchById;
use console\collectors\profile\interfaces\searchByName;

class OMDB extends searcher implements searchByName,searchById
{
    static public $api_key = 'http://www.omdbapi.com/?apikey=ad9134f0';

    public function setSupportedLanguage()
    {
       return $this->supportedLanguages = ['en-US'];
    }


    public static function searchByName($name, $year = null)
    {
        $back = self::request(self::setTitleUrl($name, $year));
        return self::initData($back);
    }

    public static function searchById($id)
    {
        $data = self::request(self::setIDUrl($id));
        return self::initData($data);
    }

    static private function initData($back)
    {
        $data = [];

        if (!isset($back['Error'])) {
            if (isset($back['Title'])) $data['vod_title'] = $back['Title'];
            if (isset($back['Title'])) $data['vod_ename'] = $back['Title'];
            if (isset($back['Year'])) $data['vod_year'] = $back['Year'];
            if (isset($back['Released'])) $data['vod_filmtime'] = date('Y-m-d', strtotime($back['Released']));
            if ($back['Runtime'] == 'N/A') $data['vod_length'] = $back['Runtime'];
            if (isset($back['Genre'])) $data['vod_type'] = $back['Genre'];
            if (isset($back['Director'])) $data['vod_director'] = $back['Director'];
            if (isset($back['Actors'])) $data['vod_actor'] = $back['Actors'];
            if (isset($back['Plot'])) $data['vod_scenario'] = $back['Plot'];
            if (isset($back['Plot'])) $data['vod_content'] = $back['Plot'];
            if (isset($back['Language'])) $data['vod_language'] = $back['Language'];
            if (isset($back['Country'])) $data['vod_area'] = $back['Country'];
            if (isset($back['Poster'])) $data['vod_pic'] = $back['Poster'];
            if (isset($back['imdbID'])) $data['vod_imdb_id'] = $back['imdbID'];
            if (isset($back['imdbRating'])) $data['vod_imdb_score'] = $back['imdbRating'];

            return $data;
        }

        return false;
    }

    private static function setTitleUrl($title, $year)
    {
        $title = str_replace(' ','+', $title);
        $url = self::$api_key . '&t=' . $title;
        if ($year) {
            $url .= "&y={$year}";
        }
        return $url;
    }

    private static function setIDUrl($id)
    {
        return self::$api_key . '&i=' . $id;
    }

    private static function request($url)
    {
        try {
            $data = file_get_contents($url);
            if ($data == false) {
                return false;
            }
            return json_decode($data, true);
        } catch (\Exception $e) {
            return false;
        }
    }
}