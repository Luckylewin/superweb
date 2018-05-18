<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ott_class".
 *
 * @property int $id
 * @property string $name 名字
 * @property string $zh_name 中文名字
 * @property string $description
 * @property string $icon 图标
 * @property string $icon_bg
 * @property int $pid 父id
 * @property string $sort 排序
 */
class OttClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'zh_name'], 'required'],
            [['id', 'pid'], 'integer'],
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
            'description' => 'Description',
            'icon' => '图标',
            'icon_bg' => 'Icon Bg',
            'pid' => '父id',
            'sort' => '排序',
        ];
    }
}
