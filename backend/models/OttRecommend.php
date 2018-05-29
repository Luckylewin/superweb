<?php

namespace backend\models;

use common\models\OttChannel;
use Yii;

/**
 * This is the model class for table "ott_recommend".
 *
 * @property int $id
 * @property int $channel_id 频道id
 * @property string $sort 排序
 * @property string $url 资源地址
 */
class OttRecommend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_recommend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channel_id'], 'required'],
            [['channel_id'], 'integer'],
            [['sort'], 'string', 'max' => 3],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => '频道id',
            'sort' => '排序',
            'url' => '资源地址',
        ];
    }

    public function getChannel()
    {
        return $this->hasOne(OttChannel::className(), ['id' => 'channel_id']);
    }

}
