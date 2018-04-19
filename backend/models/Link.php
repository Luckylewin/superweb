<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:28
 */

namespace backend\models;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "tvlink_iptv".
 *
 * @property int $ID
 * @property int $ChannelID
 * @property string $link
 * @property string $source
 * @property int $sort
 * @property int $use_flag
 * @property int $format
 * @property int $area_line
 * @property int $mass_level
 * @property string $domain
 * @property string $client
 * @property string $script_deal
 * @property string $scheme_id
 * @property string $definition
 * @property string $method
 * @property string $decode
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_tvlink';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ChannelID', 'link', 'format', 'area_line', 'mass_level'], 'required'],
            [['ChannelID', 'sort', 'use_flag', 'format', 'area_line', 'mass_level'], 'integer'],
            [['link', 'client'], 'string'],
            [['source', 'scheme_id'], 'string', 'max' => 255],
            [['domain'], 'string', 'max' => 50],
            [['script_deal', 'definition', 'decode'], 'string', 'max' => 1],
            [['method'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'ChannelID' => '频道',
            'link' => '链接',
            'source' => '来源',
            'sort' => '排序',
            'use_flag' => '是否可用',
            'format' => '格式',
            'area_line' => 'Area Line',
            'mass_level' => 'Mass Level',
            'domain' => '域名',
            'client' => '客户',
            'script_deal' => '脚本是否处理',
            'scheme_id' => '所属方案',
            'definition' => '清晰度',
            'method' => '本地方法',
            'decode' => '编码',
        ];
    }

    public function getScheme()
    {
        if ($this->scheme_id == 'all') {
            return '<div class="btn btn-success btn-xs">全部方案</div>';
        }

        $schemes = Scheme::find()->select('schemeName')->where("id not in ({$this->scheme_id})")->asArray()->all();

        $schemes = ArrayHelper::getColumn($schemes, 'schemeName');
        $str = '';

        array_walk($schemes, function ($v, $k) use (&$str) {

            $str .= sprintf("<div class='btn btn-info btn-xs' style='margin-bottom: 5px;'>%s</div>", $v) . "&nbsp" . ($k % 3 == 0 ? "<br/>" : '');
        });
        return $str;
    }
}