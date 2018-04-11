<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_app_log".
 *
 * @property int $id
 * @property string $year utf
 * @property int $month 月
 * @property int $week 第几周
 * @property string $date 日期
 * @property string $mac mac地址
 * @property string $login_time 登录时间
 * @property string $active_hour 活跃小时
 * @property string $last_time 最后一次操作时间
 * @property int $total_request 总的请求次数
 * @property int $token_request token请求次数
 * @property int $token_success token成功正确返回次数
 * @property int $ott_request 直播列表请求次数
 * @property int $iptv_request iptv请求次数
 * @property int $app_request app升级请求次数
 * @property int $firmware_request 固件升级次数
 * @property int $renew_request 续费请求次数
 * @property int $parade_request 预告请求
 * @property int $time_request 时间请求
 * @property int $register_request 注册
 * @property int $auth_request 鉴权请求
 * @property int $ip_change IP变化次数
 * @property int $request_rate 请求频率
 * @property int $exception 异常指数
 * @property string $is_valid
 * @property int $market_request
 * @property int $karaokelist_request 卡拉ok列表
 * @property int $karaoke_request 卡拉ok播放
 */
class AppLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_app_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['month', 'week', 'date', 'mac', 'login_time', 'last_time'], 'required'],
            [['date'], 'safe'],
            [['active_hour'], 'number'],
            [['total_request', 'token_request', 'token_success', 'ott_request', 'iptv_request', 'app_request', 'firmware_request', 'renew_request', 'parade_request', 'time_request', 'register_request', 'auth_request', 'ip_change', 'request_rate', 'market_request', 'karaokelist_request', 'karaoke_request'], 'integer'],
            [['year', 'month', 'exception'], 'string', 'max' => 4],
            [['week'], 'string', 'max' => 2],
            [['mac'], 'string', 'max' => 64],
            [['login_time', 'last_time'], 'string', 'max' => 8],
            [['is_valid'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'utf',
            'month' => '月',
            'week' => '第几周',
            'date' => '日期',
            'mac' => 'mac地址',
            'login_time' => '登录时间',
            'active_hour' => '活跃小时',
            'last_time' => '最后一次操作时间',
            'total_request' => '总的请求次数',
            'token_request' => 'token请求次数',
            'token_success' => 'token成功正确返回次数',
            'ott_request' => '直播列表请求次数',
            'iptv_request' => 'iptv请求次数',
            'app_request' => 'app升级请求次数',
            'firmware_request' => '固件升级次数',
            'renew_request' => '续费请求次数',
            'parade_request' => '预告请求',
            'time_request' => '时间请求',
            'register_request' => '注册',
            'auth_request' => '鉴权请求',
            'ip_change' => 'IP变化次数',
            'request_rate' => '请求频率',
            'exception' => '异常指数',
            'is_valid' => 'Is Valid',
            'market_request' => 'Market Request',
            'karaokelist_request' => '卡拉ok列表',
            'karaoke_request' => '卡拉ok播放',
        ];
    }
}
