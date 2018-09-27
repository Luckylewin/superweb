<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys__vod_scheme".
 *
 * @property int $vod_id
 * @property int $scheme_id
 */
class VodToScheme extends \yii\db\ActiveRecord
{
    public static function primaryKey()
    {
        return ['vod_id', 'scheme_id'];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys__vod_scheme';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vod_id', 'scheme_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vod_id' => 'Vod ID',
            'scheme_id' => 'Scheme ID',
        ];
    }

    public static function findByVodId($id)
    {
        return self::find()->select('scheme_id')->where(['vod_id' => $id])->column();
    }

    public static function findBySchemeId($id)
    {
        return self::find()->select('vod_id')->where(['scheme_id' => $id])->column();
    }


}
