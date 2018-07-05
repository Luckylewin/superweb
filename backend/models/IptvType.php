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

    public static function getVodType($list_id)
    {
       $type = self::find()->where(['field' => 'vod_type', 'vod_list_id' => $list_id])
                    ->with('items')
                    ->one();

       if ($type) {
          $items = $type->items;
          return ArrayHelper::map($items, 'name','name');
       }

       return [];
    }

}
