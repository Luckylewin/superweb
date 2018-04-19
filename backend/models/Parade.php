<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:16
 */

namespace backend\models;
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
            [['channel_id'], 'integer'],
            [['parade_date', 'upload_date'], 'safe'],
            [['parade_data'], 'string'],
            [['channel_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => '频道ID',
            'channel_name' => '频道名称',
            'parade_date' => '预告日期',
            'upload_date' => '采集日期',
            'parade_data' => '预告内容',
        ];
    }

    public function getChannel()
    {
        return $this->hasOne(ChannelIptv::className(), ['ID' => 'channel_id']);
    }
}
