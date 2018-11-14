<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "iptv_type_item".
 *
 * @property int $id
 * @property int $type_id 关联分类id
 * @property string $name 名称
 * @property string $zh_name 中文名称
 * @property int $sort 排序
 * @property int $exist_num 存在影片数量
 */
class IptvTypeItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_type_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'name', 'zh_name', 'sort'], 'required'],
            [['type_id', 'sort'], 'integer'],
            [['name', 'zh_name'], 'string', 'max' => 255],
            ['exist_num', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => '关联分类id',
            'name' => '名称',
            'zh_name' => '中文名称',
            'sort' => '排序',
            'exist_num' => '数量',
        ];
    }

    public function getType()
    {

        return $this->hasOne(IptvType::className(), ['id' => 'type_id']);
    }

    public static function getTypeItems($type_id)
    {
        return self::find()
                    ->where(['type_id' => $type_id])
                    ->orderBy(['exist_num' => SORT_DESC])
                    ->all();

    }

}
