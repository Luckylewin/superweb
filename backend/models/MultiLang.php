<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_multi_lang".
 *
 * @property int $id
 * @property int $fid
 * @property string $table 表名
 * @property string $field 字段名
 * @property string $origin 原值
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
            ['id', 'integer'],
            [['fid'], 'integer'],
            [['field','table'], 'string', 'max' => 20],
            [['value', 'origin'], 'string', 'max' => 800],
            [['language'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fid'   => 'Fid',
            'table' => '表名',
            'field' => '字段名',
            'value' => '值',
            'language' => '语言',
        ];
    }

    public static function loadData($supportedLanguages, $multiLanguages)
    {
        $data = array_flip($supportedLanguages);
        array_walk($data, function(&$v) {$v="";});
        if (!empty($multiLanguages)) {
            foreach ($supportedLanguages as $language) {
                foreach ($multiLanguages as $lang) {
                    if ($lang['language'] == $language) {
                        $data[$language] = [
                            'id' => $lang['id'],
                            'value' => $lang['value']
                        ];
                    }
                }
            }
        }

        foreach ($data as $lang => $val) {
            if (empty($val)) {
                $data[$lang] = ['value' => ''];
            }
        }

        return $data;
    }

}
