<?php
namespace backend\models;

use Yii;
use common\libs\Tree;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name 名称
 * @property string $icon_style 图标
 * @property string $url 路由
 * @property string $group 分组

 * @property integer $hide 是否隐藏
 * @property integer $sort
 * @property integer $display 是否显示
 * @property integer $type 类型
 */
class Menu extends \yii\db\ActiveRecord
{

    const DISPLAY = 1;
    const HIDE = 0;

    public static $displays = [
        self::DISPLAY => 'show',
        self::HIDE => 'hidden',
    ];

    public static $displayStyles = [
        self::HIDE => 'label-default',
        self::DISPLAY => 'label-info',
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['pid', 'display', 'sort'], 'integer'],
            [['name', 'icon_style'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
            [['sort'],'default','value' => '0'],
            ['type', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'pid'        => Yii::t('backend', 'Superior menu'),
            'name'       => Yii::t('backend', 'Name'),
            'url'        => Yii::t('backend', 'Route'),
            'icon_style' => Yii::t('backend', 'Icon'),
            'display'    => Yii::t('backend', 'Display Switch'),
            'sort'       => Yii::t('backend', 'Sort'),
            'type'       => Yii::t('backend', 'Type'),
        ];
    }

    public function getDisplays()
    {
       $status = self::$displays;
       array_walk($status, function(&$v) {
           return $v = Yii::t('backend', $v);
       });

       return $status;
    }

    /**
     * 获取菜单状态
     * @param $display
     * @return mixed
     */
    public static function getDisplayText($display)
    {
        return self::$displays[$display];
    }

    /**
     * 获取菜单状态样式
     * @param $display
     * @return mixed
     */
    public static function getDisplayStyle($display)
    {
        return self::$displayStyles[$display];
    }

    public static function getMenu()
    {
        $menus = static::find()->where(['display' => 1])->orderBy('sort asc')->asArray()->all();
        $treeObj = new Tree($menus);
        return $treeObj->getTreeArray();
    }

    public static function getActualMenu()
    {
        $allMenus = self::getMenu();

        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->username != 'admin') {
            foreach ($allMenus as $key => $menus) {
                foreach ($menus['_child'] as $_key => $menu) {
                    if (!\Yii::$app->user->can($menu['url'])) {
                        unset($allMenus[$key]['_child'][$_key]);
                    }
                }
                if (count($allMenus[$key]['_child']) <= 0) {
                    unset($allMenus[$key]);
                }
            }
        }

        return $allMenus;
    }



}
