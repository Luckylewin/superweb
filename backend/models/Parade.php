<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:16
 */

namespace backend\models;

use Yii;
use common\models\OttChannel;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "iptv_parade".
 *
 * @property int $id
 * @property int $channel_id
 * @property string $channel_name
 * @property string $parade_date
 * @property string $upload_date
 * @property string $parade_data
 * @property string $parade_timestamp
 * @property string $source
 * @property string $url
 * @property string $main_class_name
 */
class Parade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_parade';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'upload_date',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['upload_date'],
                ],
                'value' => date('Y-m-d')
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parade_date', 'channel_name'], 'required'],
            [['channel_id'], 'integer'],
            [['parade_date', 'upload_date', 'parade_data'], 'safe'],
            [['parade_data', 'source', 'url'], 'string'],
            [['channel_name', 'main_class_name'], 'string', 'max' => 30],
            ['parade_date', 'is_exist', 'when' => function($model) {
                return !empty($model->channel_name);
            }]
        ];
    }

    public function is_exist($attribute, $params)
    {
        $result = self::find()->where(['channel_name' => $this->channel_name, 'parade_date' => $this->parade_date])->exists();
        if ($result) {
            $this->addError($attribute,  $this->parade_date . "的" . $this->channel_name  . "预告已经存在 :(");
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => Yii::t('backend', 'Channel ID'),
            'channel_name' => Yii::t('backend', 'Channel Name'),
            'parade_date' => Yii::t('backend', 'Parade date'),
            'upload_date' => Yii::t('backend', 'Date of collection'),
            'parade_data' => Yii::t('backend', 'Parade Content'),
            'parade_timestamp' => Yii::t('backend', 'Time')
        ];
    }

    /**
     * 依靠频道的别名 进行一对多关联
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasMany(OttChannel::className(), ['alias_name' => 'channel_name'])
                    ->asArray()
                    ->all();
    }
}
