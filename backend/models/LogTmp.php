<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "log_tmp_interface".
 *
 * @property string $header
 * @property string $mac
 * @property string $data
 * @property string $code
 */
class LogTmp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_tmp_interface';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header'], 'string', 'max' => 20],
            [['mac'], 'string', 'max' => 32],
            [['data', 'code'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'header' => 'Header',
            'mac' => 'Mac',
            'data' => 'data',
            'code' => 'Code',
        ];
    }
}
