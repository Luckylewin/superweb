<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_country".
 *
 * @property int $id
 * @property string $name 英文名称
 * @property string $zh_name 中文名称
 * @property string $code 代码
 * @property string $icon 小图标
 * @property string $icon_big 大图标
 */
class SysCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'zh_name', 'code'], 'required'],
            [['name'], 'string', 'max' => 25],
            [['zh_name'], 'string', 'max' => 30],
            [['code'], 'string', 'max' => 2],
            [['icon', 'icon_big'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'zh_name' => 'Zh Name',
            'code' => 'Code',
            'icon' => 'Icon',
            'icon_big' => 'Icon Big',
        ];
    }
}
