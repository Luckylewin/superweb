<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/10
 * Time: 13:55
 */

namespace console\collectors\profile;

use backend\models\VodProfile;

/**
 * 影片资料搜索器
 * Class ProfilesSearcher
 * @package console\models\movie
 */
class ProfilesSearcher
{

    public static function quickSearchInDB($name)
    {
        // 先使用自身的数据库搜索
        if ($profile = VodProfile::findByName($name)) {
            return $profile;
        }

        return false;
    }
    /**
     * 使用方法
     * ProfilesSearcher::search('不能说的秘密')
     * @param $name
     * @return array|bool|mixed
     */
    public static function search($name)
    {
        // 先使用自身的数据库搜索
        if ($profile = VodProfile::findByName($name)) {
            return $profile;
        }

        if ($profile = IMDB::searchByName($name)) {
            self::recordToProfile($name, $profile);

            return $profile;
        }

        if ($profile = MOVIEDB::searchByName($name)) {
            self::recordToProfile($name, $profile);
            return $profile;
        }

        if ($profile = OMDB::searchByName($name)) {
            self::recordToProfile($name, $profile);
            return $profile;
        }

        return false;
    }

    public static function recordToProfile($name, $data)
    {
        if (VodProfile::find()->where(['name' => $name])->exists() == false) {
            $profile = new VodProfile();
            $profile->name = $name;
            $profile->profile = json_encode($data);
            $profile->save(false);
        }

    }
}