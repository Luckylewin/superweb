<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:28
 */

namespace backend\models;

use yii\helpers\ArrayHelper;
use Yii;

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
            'ChannelID' => Yii::t('backend', 'Channel'),
            'link' => Yii::t('backend', 'Link'),
            'source' => Yii::t('backend', 'Source identifier'),
            'sort' => Yii::t('backend', 'Sort'),
            'use_flag' => Yii::t('backend', 'Is Available'),
            'format' => Yii::t('backend', 'Format'),
            'area_line' => 'Area Line',
            'mass_level' => 'Mass Level',
            'domain' => Yii::t('backend', 'Domain'),
            'client' => Yii::t('backend', 'client'),
            'script_deal' => Yii::t('backend', 'Whether the script handles'),
            'scheme_id' => Yii::t('backend', 'Associated scheme'),
            'definition' => Yii::t('backend', 'Sharpness'),
            'method' => Yii::t('backend', 'Analytic method'),
            'decode' => Yii::t('backend', 'coding'),
        ];
    }

    public function getScheme()
    {
        if ($this->scheme_id == 'all') {
            return '<div class="btn btn-success btn-xs">'. Yii::t('backend', 'All Schemes').'</div>';
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