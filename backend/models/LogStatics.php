<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "log_statics".
 *
 * @property int $id
 * @property string $date 日期
 * @property int $active_user 活跃用户
 * @property int $valid_user 有效用户
 * @property int $total 请求总数
 * @property int $token token请求总数
 * @property int $ott_list ott节目列表请求总数
 * @property int $iptv_list iptv节目列表请求总数
 * @property int $karaoke_list 卡拉ok节目列表
 * @property int $epg 预告列表请求总数
 * @property int $app_upgrade APP升级
 * @property int $firmware_upgrade 固件升级
 * @property int $renew 会员续费
 * @property int $dvb_register dvb注册
 * @property int $ott_charge ott分类
 * @property int $pay 支付接口
 * @property int $activateGenre 激活分类使用
 * @property int $paypal_callback paypal 异步通知
 * @property int $dokypay_callback dokypay 异步通知
 * @property int $getServerTime 服务器时间
 * @property int $play 播放接口
 */
class LogStatics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_statics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_user', 'valid_user', 'total', 'token', 'ott_list', 'iptv_list', 'karaoke_list', 'epg', 'app_upgrade', 'firmware_upgrade', 'renew', 'dvb_register', 'ott_charge', 'pay', 'activateGenre', 'paypal_callback', 'dokypay_callback', 'getServerTime', 'play'], 'integer'],
            [['date'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => '日期',
            'active_user' => '活跃用户',
            'valid_user' => '有效用户',
            'total' => '请求总数',
            'token' => 'token请求总数',
            'ott_list' => 'ott节目列表请求总数',
            'iptv_list' => 'iptv节目列表请求总数',
            'karaoke_list' => '卡拉ok节目列表',
            'epg' => '预告列表请求总数',
            'app_upgrade' => 'APP升级',
            'firmware_upgrade' => '固件升级',
            'renew' => '会员续费',
            'dvb_register' => 'dvb注册',
            'ott_charge' => 'ott分类',
            'pay' => '支付接口',
            'activateGenre' => '激活分类使用',
            'paypal_callback' => 'paypal 异步通知',
            'dokypay_callback' => 'dokypay 异步通知',
            'getServerTime' => '服务器时间',
            'play' => '播放接口',
        ];
    }

    public static function findByDate($date)
    {
        return self::find()->where(['date' => $date])->limit(1)->one();
    }
}
