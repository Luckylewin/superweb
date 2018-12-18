<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/26
 * Time: 10:26
 */

namespace backend\blocks;

use Yii;
use common\models\Vod;
use backend\models\IptvType;
use yii\helpers\ArrayHelper;

class VodBlock extends Vod
{

    public static $defaultTag = [
        'comedy', 'love', 'horror', 'action', 'sci-fi', 'drama', 'war', 'vigilance', 'crime', 'martial arts', 'adventure', 'suspension', 'classic', 'youth', 'dressage', 'history', 'Animation', 'Documentary'
    ];

    public static $defaultArea = [
        'China', 'America' , 'Hong Kong', 'Taiwan', 'Korea', 'Japan', 'France', 'British', 'Germany', 'Thailand', 'India', 'Europe', 'Vietnam', 'Portugal', 'Spain'
    ];

    public static $defaultLanguage = [
        'English', 'Korean', 'French', 'Mandarin', 'Cantonese', 'Thai', 'Japanese', 'Indian', 'Spanish', 'Portuguese', 'Other'
    ];

    public static $defaultVersion = [
        'HD', 'theatrical', 'preemptive', 'theatern'
    ];

    public static $defaultType = [
        'Positive', 'Trailer','Filming'
    ];

    public $showStatus = [
        '1' => 'show',
        '0' => 'hidden'
    ];

    public static $chargeStatus = [
        '0' => 'Free',
        '1' => 'Charge'
    ];

    public static $starStatus = [
        '1' => '一星',
        '2' => '二星',
        '3' => '三星',
        '4' => '四星',
        '5' => '五星',
    ];

    // 是否付费文字
    public function getChargeText()
    {
        return self::$chargeStatus[$this->vod_ispay];
    }

    /**
     * 星星
     * @return string
     */
    public function getStar()
    {
        $str = '<font style="color:darkorange">';
        $starNum = floor($this->vod_stars);
        for ($i = 0 ; $i < $starNum; $i++) {
            $str .= '<i class="fa fa-star"><i>';
        }
        if ($starNum < 5) {
            for ($i = 0; $i <= 5 - $starNum; $i++) {
                $str .= '<i class="fa fa-star-o"><i>';
            }
        }
        $str .= '</font>';
        return $str;
    }

    /**
     * 默认标签
     * @return array
     */
    public static function getTags()
    {
        $tags = self::$defaultTag;
        array_walk($tags, function(&$v, $k) {
           $v = Yii::t('backend', $v);
        });

        return $tags;
    }

    /**
     * 默认年份
     * @return array
     */
    public static function getYears()
    {
        $years = [];
        for ($year = date('Y'); $year >= 2000; $year--) {
            $years[] = $year;
        }
        return $years;
    }

    /**
     * 默认地区
     * @return array
     */
    public static function getAreas()
    {
        $areas = self::$defaultArea;
        array_walk($areas, function(&$v, $k) {
            $v = Yii::t('backend', $v);
        });

        return $areas;
    }

    /**
     * 默认语言
     * @return array
     */
    public static function getLanguages()
    {
        $languages = self::$defaultLanguage;
        array_walk($languages, function(&$v, $k) {
            $v = Yii::t('backend', $v);
        });

        return $languages;

    }

    /**
     * 默认版本
     * @return array
     */
    public static function getVersions()
    {
        $versions = self::$defaultVersion;
        array_walk($versions, function(&$v, $k) {
            $v = Yii::t('backend', $v);
        });

        return $versions;
    }

    /**
     * 默认资源类型
     * @return array
     */
    public static function getResourceTypes()
    {
        $types = self::$defaultType;
        array_walk($types, function(&$v, $k) {
            $v = Yii::t('backend', $v);
        });

        return $types;
    }

    /**
     * 分类选择
     * @param $field
     * @return string
     */
    public function getTypeItems($field)
    {
        $type = IptvType::find()->where(['vod_list_id' => $this->vod_cid, 'field' => $field])->one();
        $tags = $this->getDefaultFields($field);

        if (!is_null($type)) {
            $item = $type->items;
            if (!is_null($item)) {
                $tags = ArrayHelper::getColumn($item, 'name');
            }
        }

        $str = '';
        foreach ($tags as $tag) {
            $str .= '<a href="javascript:;" class="select" data-id="vodblock-'. $field .'">'. $tag .'</a>&nbsp;&nbsp;';
        }
        return $str;
    }

    /**
     * 获取默认的field
     * @param $field
     * @return array
     */
    public function getDefaultFields($field)
    {
        switch ($field)
        {
            case 'vod_type':
                $tags = self::getTags();
                break;
            case 'vod_area':
                $tags = self::getAreas();
                break;

            case 'vod_year':
                $tags = self::getYears();
                break;
            case 'vod_language':
                $tags = self::getLanguages();
                break;
            case 'vod_state':
                $tags = self::getResourceTypes();
                break;
            case 'vod_version':
                $tags = self::getVersions();
                break;
            default:
                $tags = [''];
        }

        return $tags;
    }

    /**
     * 排序
     * @param $cid
     * @return bool
     */
    static public function sortAll($cid)
    {
        $items = self::find()->where(['vod_cid' => $cid])->orderBy([
            'is_top' => SORT_DESC,
            'vod_addtime' => SORT_ASC,
            'sort' => SORT_ASC,
        ])->all();

        $start = 0;

        if (!empty($items)) {
            foreach ($items as $item) {
                $item->sort = $start += 2;
                $item->save(false);
            }
        }

        return true;
    }

    /**
     * @param $action
     * @param $vod_cid
     * @param $id
     * @return bool|int|string
     */
    static public function sortUpDown($action, $vod_cid, $id, $compare_id)
    {
        $model = self::findOne($id);
        if ($model) {

            if ($action == 'up') {
                $smallOne = VodBlock::findOne($compare_id);
                if ($smallOne && $smallOne->sort > 1) {
                    $model->sort = $smallOne->sort - 1;
                } else if ($model->sort > 1){
                    $model->sort -= 1;
                } else {
                    $model->sort = 0;
                }

            } else {
                // 找一个比他大的
                $bigOne = VodBlock::findOne($compare_id);

                if ($bigOne) {
                    $model->sort = $bigOne->sort + 1;
                } else {
                    $model->sort += 1;
                }

            }
            $model->save(false);
            return $model->sort;

        }

        return false;
    }

    static public function setSort($id, $sort)
    {
        $model = self::findOne($id);
        if ($model) {
            $model->sort = $sort;
            $model->save(false);
            return $model->sort ;
        }

        return false;
    }

}