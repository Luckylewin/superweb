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
            'en_US'=>'英语',
            'zh_CN'=>'中文',
            'es_ES'=>'西班牙语',
            'pt_PT'=>'葡萄牙语',
            'vi_VN'=>'越南语',
            'ar_AE'=>'阿拉伯语',
            'pt_BR'=>'葡萄牙语(巴西)',
            'es_US'=>'西班牙语(美国)',
            'zh_TW'=>'中文台湾',
            'zh_HK'=>'中文香港',
            'th_TH'=>'泰语',
            'fr_FR'=>'法语',
            'da_DK'=>'丹麦语',
            'de_DE'=>'德语',
            'ru_RU'=>'俄语',
            'it_IT'=>'意大利语',
            'ja_JP'=>'日语',
            'ko_KR'=>'韩语',
            'sv_SE'=>'瑞典语',
            'el_GR'=>'希腊语'
        ];
    }
}