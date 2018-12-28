<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/10
 * Time: 13:55
 */

namespace console\collectors\profile;

use backend\models\VodProfile;
use backend\models\VodProfiles;

/**
 * 影片资料搜索器
 *
 * 使用方法
 * ProfilesSearcher::search('不能说的秘密',[
 *     'language' => 'zh-CN'
 * ]);
 *
 *
 * Class ProfilesSearcher
 * @package console\models\movie
 */
class ProfilesSearcher
{

    public static function quickSearchInDB($name, $language = 'en-US')
    {
        // 豆瓣最基本的数据
        $douban = VodProfiles::findByName($name);
        $profile = VodProfile::findByName($name, $language);

        if ($profile && $douban) {
            foreach ($profile as $key => $value) {
                if (empty($profile[$key]) && !empty($douban[$key])) {
                    $profile[$key] = $douban[$key];
                }
                if ($key == 'vod_actor' && !empty($douban[$key])) {
                    $profile[$key] = $douban[$key];
                }
            }
        }

        if($profile) return $profile;

        if ($douban) return $douban;

        return false;
    }

    private static function searchAsYouCan($name, $options)
    {
        $language = $options['language']??'zh-US';

        // 再使用历史搜索 搜索
        if ($profile = VodProfile::findByName($name, $language)) {
            return $profile;
        }

        if (static::isSupported(new IMDB(), $language) && $profile = IMDB::searchByName($name, $options)) {
            self::recordToProfile($name, $profile, $language);
            sleep(1);
            return $profile;
        }

        if (static::isSupported(new MOVIEDB(), $language) && $profile = MOVIEDB::searchByName($name, $options)) {
            self::recordToProfile($name, $profile, $language);
            sleep(1);
            return $profile;
        }

        if (static::isSupported(new OMDB(), $language) && $profile = OMDB::searchByName($name, $options)) {
            self::recordToProfile($name, $profile, $language);
            sleep(1);

            return $profile;
        }

        return false;
    }

    /**
     * 使用方法
     * ProfilesSearcher::search('不能说的秘密')
     * @param $name string
     * @param $options array
     * @return array|bool|mixed
     */
    public static function search($name, $options = [])
    {
        // 豆瓣最基本的数据
        $douban = VodProfiles::findByName($name);

        $profile = self::searchAsYouCan($name, $options);
        if ($profile && $douban) {
            foreach ($profile as $key => $value) {
                if (empty($profile[$key]) && !empty($douban[$key])) {
                    $profile[$key] = $douban[$key];
                }
                if ($key == 'vod_actor' && !empty($douban[$key])) {
                    $profile[$key] = $douban[$key];
                }
            }
        }

        if($profile){
            return $profile;
        }

        if ($douban) {
            return $douban;
        }

        return false;
    }


    /**
     * 判断该搜索器是否支持该语言
     * @param $language
     * @param $object searcher
     * @return bool
     */
    protected static function isSupported($object,$language)
    {
        if (in_array($language, $object->getSupportedLanguage())) {
            return true;
        }

        return false;
    }

    public static function recordToProfile($name, $data, $language)
    {
        if (VodProfile::find()->where(['name' => $name])->exists() == false) {
            $profile = new VodProfile();
            $profile->name = $name;
            $profile->language = $language;
            $profile->profile = json_encode($data);
            $profile->save(false);
        }

    }
}