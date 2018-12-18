<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "log_ott_genre_tmp".
 *
 * @property int $id
 * @property string $mac
 * @property string $genre
 * @property int $code
 */
class LogOttTmp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_ott_genre_tmp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mac'], 'safe'],
            [['code'], 'integer'],
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
            'mac' => 'Mac',
            'genre' => 'Genre',
            'code' => 'Code',
        ];
    }
}
