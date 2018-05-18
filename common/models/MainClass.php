<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ott_main_class".
 *
 * @property int $id
 * @property string $name 名字
 * @property string $zh_name 中文名字
 * @property string $description
 * @property string $icon 图标
 * @property string $icon_bg
 * @property string $sort 排序
 */
class MainClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_main_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'zh_name'], 'required'],
            [['name', 'zh_name', 'description', 'icon', 'icon_bg'], 'string', 'max' => 255],
            [['sort'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名字',
            'zh_name' => '中文名字',
            'description' => '简介',
            'icon' => '图标',
            'icon_bg' => 'Icon Bg',
            'sort' => '排序',
        ];
    }

    /**
     * 与子类的 关联关系
     * @return \yii\db\ActiveQuery
     */
    public function getSub()
    {
        return $this->hasOne(SubClass::className(), ['main_class_id' => 'id'])->inverseOf('main');
    }
}
