<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ott_price_list".
 *
 * @property int $id
 * @property string $text 时间文本
 * @property string $price 价格
 * @property string $value 值
 * @property string $type
 */
class OttPriceList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_price_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['price'], 'required'],
            [['value'], 'string', 'max' => 2],
            [['type'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => '价格',
            'value' => '时间',
            'type' => '类型',
        ];
    }

    public function getText()
    {
        $text = ['1'=>'1个月', '3'=>'3个月', '6'=>'6个月', '12'=>'12个月'];

        return $text[$this->value];
    }

}
