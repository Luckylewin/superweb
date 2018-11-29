<?php

namespace backend\models;

use common\models\VodList;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "iptv_type".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $field 字段
 * @property int $vod_list_id 关联类型id
 * @property int $sort 排序
 * @property int $image 图片
 * @property int $image_hover  高亮图片
 * @property int $is_show  是否显示
 */
class IptvType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'field', 'vod_list_id'], 'required'],
            [['vod_list_id'], 'integer'],
            [['name', 'field'], 'string', 'max' => 20],
            ['sort', 'default', 'value' => 0],
            [['image','image_hover','is_show'], 'safe'],
            ['is_show', 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'field' => '字段',
            'vod_list_id' => '关联类型id',
            'sort' => '排序',
            'image' => '图标',
            'image_hover' => '高亮图标',
            'is_show' => '是否显示'
        ];
    }

    /**
     * 获取所拥有的子元素
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(IptvTypeItem::className(), ['type_id' => 'id']);
    }

    /**
     * 获取所属的分类
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(VodList::className(), ['list_id' => 'vod_list_id']);
    }

    public function getMultiLang()
    {
        return $this->hasMany(MultiLang::className(), ['fid' => 'id']);
    }

    public static function getTypeItem($list_id, $field)
    {
        $type = self::find()->where(['field' => $field])
                            ->orWhere(['or', ['=', 'field', str_replace('vod_', '', $field)]])
                            ->andWhere(['vod_list_id' => $list_id])
                            ->with([
                                'items' => function($query) {
                                    $query->where(['>', 'exist_num', 0]);
                                }
                            ])
                            ->one();

        if ($type) {
            $items = $type->items;
            return ArrayHelper::map($items, 'name','name');
        }

        return [];
    }

    public function beforeDelete()
    {
       $items = $this->getItems()->all();
       if ($items) {
           foreach ($items as $item) {
               $item->delete();
           }
       }

       MultiLang::deleteAll(['fid' => $this->id, 'table' => self::tableName()]);

       return true;
    }

}
