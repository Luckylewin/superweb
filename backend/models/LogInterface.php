<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "log_interface".
 *
 * @property int $id
 * @property string $year 年份
 * @property string $date 日期
 * @property string $total 请求总数
 * @property string $watch 节目播放请求
 * @property string $getClientToken token请求
 * @property string $getOttNewList ott列表下载
 * @property string $getIptvList 点播列表下载
 * @property string $getAppMarket APP市场
 * @property string $renew 续费
 * @property string $getNewApp app更新
 * @property string $ottCharge 下单接口
 * @property string $register 注册请求
 * @property string $getMajorEvent 获取主要赛事
 * @property string $getOttRecommend 获取直播推荐
 * @property string $getCountryList 获取国家列表
 * @property string $notify 支付异步/同步通知
 */
class LogInterface extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_interface';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date'], 'safe'],
            [['year'], 'string', 'max' => 255],
            [['total', 'watch', 'getClientToken', 'getOttNewList', 'getIptvList', 'getAppMarket', 'renew', 'getNewApp', 'ottCharge', 'register', 'getMajorEvent', 'getOttRecommend', 'getCountryList', 'notify'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => '年份',
            'date' => '日期',
            'total' => '请求总数',
            'watch' => '节目播放请求',
            'getClientToken' => 'token请求',
            'getOttNewList' => 'ott列表下载',
            'getIptvList' => '点播列表下载',
            'getAppMarket' => 'APP市场',
            'renew' => '续费',
            'getNewApp' => 'app更新',
            'ottCharge' => '下单接口',
            'register' => '注册请求',
            'getMajorEvent' => '获取主要赛事',
            'getOttRecommend' => '获取直播推荐',
            'getCountryList' => '获取国家列表',
            'notify' => '支付异步/同步通知',
        ];
    }

    // 日期统计
    static public function findByDate($date)
    {
        $data = self::find()->where(['date' => $date])->one();
        $fields = ['watch', 'total', 'notify', 'getCountryList', 'getOttRecommend', 'getMajorEvent', 'register', 'ottCharge', 'getNewApp', 'renew', 'getAppMarket', 'getIptvList', 'getOttNewList', 'getClientToken'];

        foreach ($fields as $field) {
            if (isset($data->$field)) {
                $data->$field = implode(',', json_decode($data->$field, true));
            }
        }

        return $data;
    }

    // 月份统计
    public static function findByMonth($year, $month)
    {
        $data = self::find()->where(['year' => $year])->andWhere(['like','date',"{$month}-"])->asArray()->all();

        $fields = ['watch', 'total', 'notify', 'getCountryList', 'getOttRecommend', 'getMajorEvent', 'register', 'ottCharge', 'getNewApp', 'renew', 'getAppMarket', 'getIptvList', 'getOttNewList', 'getClientToken'];

        array_walk($data, function(&$item) use ($fields) {
            foreach ($fields as $field) {
                $item[$field] = json_decode($item[$field], true);
            }
        });

        $monthData = [];

        foreach ($fields as $field) {
                for ($h=0; $h<=23; $h++) {
                    $hourTotal = 0;
                    foreach ($data as $key => $item) {
                        $hourTotal += $item[$field][$h];
                    }
                    $monthData[$field][$h] = floor($hourTotal/30);
                }

            $monthData[$field] = implode(',', $monthData[$field]);
        }

        return json_decode(json_encode($monthData));
    }

}
