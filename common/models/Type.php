<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/8
 * Time: 13:29
 */

namespace common\models;


class Type
{
    const LANGUAGE_CHINESE    = 'Chinese';
    const LANGUAGE_ENGLISH    = 'English';
    const LANGUAGE_VIETNAMESE = 'Vietnamese';
    const LANGUAGE_HINDI      = 'Hindi';
    const LANGUAGE_THAT       = 'Thai';
    const LANGUAGE_KOREAN     = 'Korean';
    const LANGUAGE_JAPANESE   = 'Japanese';

    const AREA_CHINA    = 'China';
    const AREA_VIETNAM  = 'Vietnam';
    const AREA_INDIA    = 'India';
    const AREA_THAILAND = 'Thailand';
    const AREA_KOREA    = 'Korea';
    const AREA_JAPAN    = 'Japan';
    const AREA_AMERICA    = 'Japan';

    public static function getLangCode()
    {
        return [
            'en-us'=>'英语',
            'zh-cn'=>'中文',
            'pt-br'=>'葡萄牙语',
            'vi-vn'=>'越南语',
            'es-es'=>'西班牙语',
            'ar'   =>'阿拉伯语',
            'th-th'=>'泰语',
            'fr-fr'=>'法语',
            'zh-tw'=>'繁体中文',
            'da-dk'=>'丹麦语',
            'nl-nl'=>'荷兰语',
            'fi-fi'=>'芬兰语',
            'de-de'=>'德语',
            'ru-ru'=>'俄语',
            'it-it'=>'意大利语',
            'ja-jp'=>'日语',
            'ko-kr'=>'韩语',
            'sv-se'=>'瑞典语',
            'el-gr'=>'希腊语',
            'pl-pl' => '波兰语',
            'fi-FI' => '芬兰语',
        ];
    }
}