<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/12
 * Time: 17:55
 */

namespace console\collectors\profile;


use console\collectors\common;
use console\collectors\profile\interfaces\searchByName;

class YIBA extends searcher implements searchByName
{
    protected static $url = 'https://movie.douban.com/subject_search?search_text=';

    public function setSupportedLanguage()
    {
        return $this->supportedLanguages = 'zh-CN';
    }

    public static function searchByName($name, $options)
    {
        $common = new common();
        $client = $common->getHttpClient();

        $dom = $common->getDom(static::getQueryUrl($name));
        echo $dom->html();
    }

    public static function getQueryUrl($name)
    {
        return self::$url . $name;
    }

}