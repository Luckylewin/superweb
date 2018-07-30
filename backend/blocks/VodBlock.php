<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/26
 * Time: 10:26
 */

namespace backend\blocks;


use common\models\Vod;
use backend\models\IptvType;
use yii\helpers\ArrayHelper;

class VodBlock extends Vod
{


    public $showStatus = [
        '1' => '显示',
        '0' => '隐藏'
    ];

    public static $chargeStatus = [
        '0' => '免费点播',
        '1' => 'VIP点播'
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
        return ['喜剧','爱情','恐怖','动作','科幻','剧情','战争','警匪','犯罪','动画','奇幻','武侠','冒险','枪战','恐怖','悬疑','惊悚','经典','青春','文艺','古装','历史','体育','儿童','微电影'];
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
        return ['中国大陆','美国','中国香港','中国台湾','韩国','日本','法国','英国','德国','泰国','印度','欧洲','东南亚','其他'];
    }

    /**
     * 默认语言
     * @return array
     */
    public static function getLanguages()
    {
        return ['国语','英语','粤语','泰语','韩语','日语','印度语','其它'];
    }

    /**
     * 默认版本
     * @return array
     */
    public static function getVersions()
    {
        return ['高清版','剧场版','抢先版','OVA','TV','影院版'];
    }

    /**
     * 默认资源类型
     * @return array
     */
    public static function getResourceTypes()
    {
        return ['正片','预告片','花絮'];
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
                $tags = ArrayHelper::getColumn($item, 'zh_name');
            }
        }

        $str = '';
        foreach ($tags as $tag) {
            $str .= '<a href="javascript:;" class="select" data-id="vod-'. $field .'">'. $tag .'</a>&nbsp;&nbsp;';
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
        $items = self::find()->where(['vod_cid' => $cid])->all();

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