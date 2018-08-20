<?php

namespace common\models;

use backend\models\LinkToScheme;
use backend\models\Scheme;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ott_link".
 *
 * @property int $id
 * @property int $channel_id 关联频道号
 * @property string $link 链接
 * @property string $source 来源
 * @property int $sort 排序
 * @property string $use_flag 可用
 * @property int $format
 * @property string $script_deal 脚本开关
 * @property string $definition 清晰度
 * @property string $method 本地算法
 * @property string $decode 硬软解
 * @property string $scheme_id 方案号
 */
class OttLink extends \yii\db\ActiveRecord
{
    const AVAILABLE = 1;
    const DISABLE = 0;

    const HARD_DECODE = 0;
    const SOFT_DECODE = 1;

    public static $decode_status = ['Hard Decode', 'Soft Decode'];

    public static $switch_status = ['Off','On',];

    public $use_flag_status = ['Unavailable', 'Available',];

    public $use_flag_text;
    public $schemeText;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channel_id', 'link', 'source'], 'required'],
            [['channel_id', 'sort', 'format'], 'integer'],
            [['link'], 'string'],
            [['source'], 'string', 'max' => 30],
            [['use_flag', 'script_deal', 'definition', 'decode'], 'string', 'max' => 1],
            [['method'], 'string', 'max' => 20],
            [['use_flag'], 'default', 'value' => 1],
            ['format','default', 'value' => '0'],
            ['scheme_id', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => Yii::t('backend', 'Associated Channel'),
            'link' => Yii::t('backend', 'Link'),
            'source' => Yii::t('backend', 'Source'),
            'sort' => Yii::t('backend', 'Sort'),
            'use_flag' => Yii::t('backend', 'Available'),
            'format' => Yii::t('backend', 'Format'),
            'script_deal' => Yii::t('backend', 'Script switch'),
            'definition' => Yii::t('backend', 'Sharpness'),
            'method' => Yii::t('backend', 'Analytic method'),
            'decode' => Yii::t('backend', 'coding'),
        ];
    }

    public function fields()
    {
        return [
            'id',
            'channel_id',
            'link',
            'source' ,
            'sort',
            'use_flag' ,
            'format',
            'script_deal',
            'definition',
            'method',
            'decode',
            'schemeText',
            'use_flag_text',
            'scheme_id'
        ];
    }
    
    public function beforeValidate()
    {
        parent::beforeValidate();

        if (is_array($this->scheme_id)) {
            $count = Scheme::find()->count('id');
            if ($count == count($this->scheme_id)) {
                $this->scheme_id = 'all';
            } else {
                $this->scheme_id = implode(',', $this->scheme_id);
            }

        }
        return true;
    }

    public function afterFind()
    {
        parent::afterFind();

        if (isset($this->use_flag_status[$this->use_flag])) {
            $this->use_flag_text = $this->use_flag_status[$this->use_flag];
        }
        
        if ($this->scheme_id == 'all') {
            $this->schemeText = '全部';
        } elseif (!empty($this->scheme_id)){
            $schemeName = Scheme::find()->select('schemeName')->where("id in ({$this->scheme_id})")->all();
            if (!empty($schemeName)) {
                $schemeName = ArrayHelper::getColumn($schemeName, 'schemeName');
                $this->schemeText = implode(',', $schemeName);
            }
        }
    }

    /**
     * 频道关联关系
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(OttChannel::className(), ['id' => 'channel_id']);
    }

    public static function getDecodeStatus()
    {
        $options = self::$decode_status;
        array_walk($options, function(&$v) {
            $v = Yii::t('backend', $v);
        });

        return $options;
    }

    public static function getSwitchStatus()
    {
        $options = self::$switch_status;
        array_walk($options, function(&$v) {
            $v = Yii::t('backend', $v);
        });

        return $options;
    }

}
