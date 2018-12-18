<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "log_ott_activity".
 *
 * @property int $id
 * @property string $date
 * @property int $timestamp
 * @property string $mac
 * @property string $genre
 * @property string $code
 */
class LogOttActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_ott_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['timestamp'], 'integer'],
            [['mac', 'code'], 'string', 'max' => 32],
            [['genre'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'timestamp' => 'Timestamp',
            'mac' => 'Mac',
            'genre' => 'Genre',
            'code' => 'Code',
        ];
    }
}
