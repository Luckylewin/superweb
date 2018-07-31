<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys__admin_scheme".
 *
 * @property int $scheme_id
 * @property int $admin_id
 */
class AdminToScheme extends \yii\db\ActiveRecord
{

    static public function primaryKey()
    {
        return ['scheme_id', 'admin_id'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys__admin_scheme';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scheme_id', 'admin_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scheme_id' => 'Scheme ID',
            'admin_id' => 'Admin ID',
        ];
    }
}
