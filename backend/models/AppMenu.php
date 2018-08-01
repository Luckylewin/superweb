<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "app_menu".
 *
 * @property int $id
 * @property string $name 名字
 * @property string $zh_name 图标
 * @property string $icon 图标
 * @property string $icon_hover 图标高亮
 * @property string $icon_big 大图标
 * @property string $icon_big_hover 图标大(高亮)
 * @property string $restful_url restful地址
 * @property string $auth 是否需权限访问
 * @property string $sort 排序
 */
class AppMenu extends \yii\db\ActiveRecord
{

    public static $isAuth = ["No", "Yes"];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'zh_name', 'restful_url'], 'required'],
            [['name', 'zh_name'], 'string', 'max' => 50],
            [['icon', 'icon_hover', 'icon_big', 'icon_big_hover', 'restful_url', 'auth'], 'string', 'max' => 255],
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
            'name' => Yii::t('backend', 'Name'),
            'zh_name' => Yii::t('backend', 'Chinese name'),
            'icon' => Yii::t('backend', 'Icon'),
            'icon_hover' => Yii::t('backend', 'Icon(hover)'),
            'icon_big' => Yii::t('backend', 'Big graph'),
            'icon_big_hover' => Yii::t('backend', 'Big graph(hover)'),
            'restful_url' => Yii::t('backend', 'Resource address'),
            'auth' => Yii::t('backend', 'Permission access'),
            'sort' => Yii::t('backend', 'Sort'),
        ];
    }
}
