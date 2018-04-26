<?php

namespace common\models;

use Yii;
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;
/**
 * This is the model class for table "iptv_list".
 *
 * @property int $list_id
 * @property int $list_pid 父id
 * @property int $list_sid 模型id
 * @property string $list_name 分类名称
 * @property string $list_dir 分类英文别名
 * @property int $list_status
 * @property string $list_keywords 分类SEO关键词
 * @property string $list_title 分类SEO标题
 * @property string $list_description 分类SEO描述
 * @property int $list_ispay 影片观看权限
 * @property int $list_price 影片单独付费
 * @property int $list_trysee 影片试看时间
 * @property string $list_extend 扩展配置
 * @property string $list_icon 图标
 */
class VodList extends \yii\db\ActiveRecord implements Linkable
{
    public $icon;

    private $list_status = [
        '关闭',
        '正常'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['list_pid', 'list_price', 'list_trysee'], 'integer'],
            [['list_name'], 'required'],
            [['list_extend'], 'string'],
            [['list_sid', 'list_status', 'list_ispay'], 'integer', 'max' => 1],
            [['list_name'], 'string', 'max' => 20],
            [['list_dir'], 'string', 'max' => 90],
            [['list_keywords', 'list_description'], 'string', 'max' => 255],
            [['list_title'], 'string', 'max' => 50],
            ['list_sid', 'default', 'value' => 1],
            ['list_trysee', 'default', 'value' => 5],
            [['list_name','list_dir'], 'unique'],
            ['list_price', 'default', 'value' => 0],
            [['icon', 'list_icon'], 'safe']
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->list_keywords)) {
                $this->list_keywords = $this->list_name;
            }
            if (empty($this->list_description)) {
                $this->list_description = $this->list_name;
            }
            if (empty($this->list_title)) {
                $this->list_title = $this->list_name;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'list_id' => 'List ID',
            'list_pid' => '父id',
            'list_sid' => '模型id',
            'list_name' => '分类名称',
            'list_dir' => '分类英文别名',
            'list_status' => 'List Status',
            'list_keywords' => '分类SEO关键词',
            'list_title' => '分类SEO标题',
            'list_description' => '分类SEO描述',
            'list_ispay' => '影片观看权限',
            'list_price' => '影片付费',
            'list_trysee' => '影片试看时间(分)',
            'list_extend' => '扩展配置',
            'list_icon' => '图标',
        ];
    }

    public function fields()
    {
        return [
            'list_id',
            'list_name',
            'list_dir',
            'list_ispay',
            'list_price',
            'list_trysee',
            'list_icon'
        ];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['type/view', 'id' => $this->list_id], true),
            'index' => Url::to(['type/index'], true),
            'vod' => Url::to(['vod/index', 'cid'=> $this->list_id], true)
        ];
    }

    public static function getAllList()
    {
        return self::find()->select('list_id,list_name')->all();
    }
}
