<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys__apk_scheme".
 *
 * @property int $apk_id
 * @property int $scheme_id
 */
class ApkToScheme extends \yii\db\ActiveRecord
{

    static public function primaryKey()
    {
        // return 'id';
        // 对于复合主键，要返回一个类似如下的数组
        return array('apk_id', 'scheme_id');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys__apk_scheme';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apk_id', 'scheme_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'apk_id' => 'Apk',
            'scheme_id' => '关联方案号',
        ];
    }
}
