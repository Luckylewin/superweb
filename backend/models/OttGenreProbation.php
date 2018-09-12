<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ott_genre_probation".
 *
 * @property string $mac
 * @property string $genre 列表名称
 * @property string $day
 * @property int $expire_time 过期时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class OttGenreProbation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_genre_probation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mac', 'day'], 'required'],
            [['day'], 'safe'],
            [['expire_time', 'created_at', 'updated_at'], 'integer'],
            [['mac'], 'string', 'max' => 32],
            [['genre'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mac' => 'Mac',
            'genre' => '列表名称',
            'day' => 'Day',
            'expire_time' => '过期时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
