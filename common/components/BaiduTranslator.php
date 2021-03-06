<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/6/11
 * Time: 17:53
 */

namespace common\components;

use Yii;

class BaiduTranslator
{
    const URL = 'http://api.fanyi.baidu.com/api/trans/vip/translate';
    const CURL_TIMEOUT = 10;
    private static $APP_ID;
    private static $SEC_KEY;

    public static function convertCode($code)
    {

        $codes = [
            'zh_CN' => ['bcode' => 'zh', 'name' => '中文'],
            'en_US' => ['bcode' => 'en','name' => '英语'],
            'ja_JP' => ['bcode' => 'jp','name' => '日语'],
            'ko_KR' => ['bcode' => 'kor','name' => '韩语'],
            'fr_FR' => ['bcode' => 'fra','name' => '法语'],
            'de_DE' => ['bcode' => 'de','name'=>'德语'],
            'ru_RU' => ['bcode' => 'ru','name'=>'俄语'],
            'th_TH' => ['bcode' => 'th','name'=>'泰语'],
            'es_ES' => ['bcode' => 'spa','name'=>'西班牙语'],
            'el_GR' => ['bcode' => 'el','name'=>'希腊语'],
            'vi_VN' => ['bcode' => 'vie','name'=>'越南语'],
            'ar_AE' => ['bcode' => 'ara','name' => '阿拉伯语'],
            'pt_PT' => ['bcode' => 'pt','name' => '葡萄牙语'],
            'it_IT' => ['bcode' => 'it','name' => '意大利语'],
            'da_DK' => ['bcode' => 'dan','name'=>'丹麦语'],
            'sv_SE' => ['bcode' => 'swe','name'=>'瑞典语'],
            'pt_BR' => ['bcode' => 'pt','name' => '葡萄牙语'],
            'zh_TW' => ['bcode' => 'cht','name' => '中文繁体'],
            'zh_HK' => ['bcode' => 'cht','name' => '中文繁体'],
            'fi-fi' => ['bcode' => 'fin','name' => '芬兰语'],
        ];

        if (array_key_exists($code, $codes)) {
            return $codes[$code]['bcode'];
        }

        return false;
    }

    //翻译入口
    static public function translate($query, $from, $to)
   {
        self::$APP_ID  = Yii::$app->params['BAIDU_TRANSLATE']['APP_ID'];
        self::$SEC_KEY = Yii::$app->params['BAIDU_TRANSLATE']['SEC_KEY'];

        $args = array(
            'q' => $query,
            'appid' => self::$APP_ID,
            'salt' => rand(10000,99999),
            'from' => $from,
            'to'   => $to,

        );

        $args['sign'] = self::buildSign($query,  self::$APP_ID, $args['salt'], self::$SEC_KEY);
        $ret = self::call(self::URL, $args);
        $ret = json_decode($ret, true);

        if (isset($ret['error_code'])) {
            return $query;
        }

        return $ret['trans_result'][0]['dst'];
    }

//加密
    static public function buildSign($query, $appID, $salt, $secKey)
    {/*{{{*/
        $str = $appID . $query . $salt . $secKey;
        $ret = md5($str);
        return $ret;
    }/*}}}*/

//发起网络请求
    static public function call($url, $args=null, $method="post", $testflag = 0, $timeout = self::CURL_TIMEOUT, $headers=array())
    {/*{{{*/
        $ret = false;
        $i = 0;
        while($ret === false)
        {
            if($i > 1)
                break;
            if($i > 0)
            {
                sleep(1);
            }
            $ret = self::callOnce($url, $args, $method, false, $timeout, $headers);
            $i++;
        }
        return $ret;
    }/*}}}*/

    static public function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = self::CURL_TIMEOUT, $headers=array())
    {/*{{{*/
        $ch = curl_init();
        if($method == "post")
        {
            $data = self::convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        else
        {
            $data = self::convert($args);
            if($data)
            {
                if(stripos($url, "?") > 0)
                {
                    $url .= "&$data";
                }
                else
                {
                    $url .= "?$data";
                }
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(!empty($headers))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if($withCookie)
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }/*}}}*/

    public static function convert(&$args)
    {/*{{{*/
        $data = '';
        if (is_array($args))
        {
            foreach ($args as $key=>$val)
            {
                if (is_array($val))
                {
                    foreach ($val as $k=>$v)
                    {
                        $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                    }
                }
                else
                {
                    $data .="$key=".rawurlencode($val)."&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }/*}}}*/

}