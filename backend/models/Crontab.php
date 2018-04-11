<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/10
 * Time: 18:21
 */

namespace backend\models;

use Yii;
use console\components\CronParser;


/**
 * This is the model class for table "sys_crontab".
 *
 * @property int $id
 * @property string $name 定时任务名称
 * @property string $route 任务路由
 * @property string $crontab_str crontab格式
 * @property int $switch 任务开关 0关闭 1开启
 * @property int $status 任务运行状态 0正常 1任务报错
 * @property string $last_rundate 任务上次运行时间
 * @property string $next_rundate 任务下次运行时间
 * @property string $execmemory 任务执行消耗内存(单位/byte)
 * @property string $exectime 任务执行消耗时间
 */
class Crontab extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_crontab';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'route', 'crontab_str'], 'required'],
            [['last_rundate', 'next_rundate'], 'safe'],
            [['execmemory', 'exectime'], 'number'],
            [['name', 'route', 'crontab_str'], 'string', 'max' => 50],
            [['switch'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '定时任务名称',
            'route' => '任务路由',
            'crontab_str' => 'crontab格式',
            'switch' => '任务开关 0关闭 1开启',
            'status' => '任务运行状态 0正常 1任务报错',
            'last_rundate' => '上次运行时间',
            'next_rundate' => '下次运行时间',
            'execmemory' => '内存消耗(b)',
            'exectime' => '耗时',
            'switchText' => '开关',
            'statusText' => '运行状态'
        ];
    }

    /**
     * switch字段的文字映射
     * @var array
     */
    private $switchTextMap = [
        0 => '关闭',
        1 => '开启',
    ];

    /**
     * status字段的文字映射
     * @var array
     */
    private $statusTextMap = [
        0 => '正常',
        1 => '任务保存',
    ];

    public function getSwitchItems()
    {
        return $this->switchTextMap;
    }

    /* public static function getDb()
     {
         #注意!!!替换成自己的数据库配置组件名称
         return Yii::$app->tfbmall;
     }*/
    /**
     * 获取switch字段对应的文字
     * @author jlb
     * @return ''|string
     */
    public function getSwitchText()
    {
        if(!isset($this->switchTextMap[$this->switch])) {
            return '';
        }
        return $this->switchTextMap[$this->switch];
    }

    /**
     * 获取status字段对应的文字
     * @author jlb
     * @return ''|string
     */
    public function getStatusText()
    {
        if(!isset($this->statusTextMap[$this->status])) {
            return '';
        }
        return $this->statusTextMap[$this->status];
    }

    /**
     * 计算下次运行时间
     * @author jlb
     */
    public function getNextRunDate()
    {
        if (!CronParser::check($this->crontab_str)) {
            throw new \Exception("格式校验失败: {$this->crontab_str}", 1);
        }
        return CronParser::formatToDate($this->crontab_str, 1)[0];
    }

}
