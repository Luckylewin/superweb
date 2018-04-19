<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/19
 * Time: 15:20
 */

namespace backend\models;

/**
 * This is the model class for table "ds_country".
 *
 * @property int $id
 * @property string $name
 * @property string $zh_name
 * @property string $code
 * @property string $code2
 * @property int $is_show 是否显示 1 显示 0 不显示
 * @property int $sort
 * @property string $commonpic
 * @property string $hoverpic
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ds_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'zh_name'], 'required'],
            [['sort'], 'integer'],
            [['name', 'zh_name'], 'string', 'max' => 50],
            [['code', 'code2'], 'string', 'max' => 5],
            [['is_show'], 'string', 'max' => 2],
            [['commonpic', 'hoverpic'], 'string', 'max' => 255],
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
            'zh_name' => '中文',
            'code' => '代码I',
            'code2' => '代码II',
            'is_show' => '是否显示',
            'sort' => '排序',
            'commonpic' => 'Commonpic',
            'hoverpic' => 'Hoverpic',
        ];
    }
}
