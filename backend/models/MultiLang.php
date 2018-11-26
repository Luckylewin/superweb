<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_multi_lang".
 *
 * @property int $fid
 * @property string $field 字段名
 * @property string $value 值
 * @property string $language 语言
 */
class MultiLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_multi_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid'], 'integer'],
            [['field'], 'string', 'max' => 20],
            [['value'], 'string', 'max' => 800],
            [['language'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fid' => 'Fid',
            'field' => '字段名',
            'value' => '值',
            'language' => '语言',
        ];
    }
}
