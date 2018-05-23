<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ott_sub_class".
 *
 * @property int $id
 * @property int $main_class_id
 * @property string $name
 * @property string $zh_name
 * @property int $sort
 * @property int $use_flag
 * @property string $keyword 导入识别关键字
 * @property int $created_at
 */
class SubClass extends \yii\db\ActiveRecord
{
    public $use_flag_text = ['不可用', '可用'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_sub_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['main_class_id', 'name', 'zh_name'], 'required'],
            [['main_class_id', 'sort', 'use_flag', 'created_at'], 'integer'],
            [['name', 'zh_name', 'keyword'], 'string', 'max' => 255],
            ['use_flag', 'default', 'value' => 1],
            ['sort', 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'main_class_id' => '大类ID',
            'name' => '名称',
            'zh_name' => '中文名称',
            'sort' => '排序',
            'use_flag' => '是否可用',
            'keyword' => '导入识别关键字',
            'created_at' => '创建时间',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => ['created_at'],
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ]
            ]
        ];
    }

    /**
     * 关联大类
     * @return \yii\db\ActiveQuery
     */
    public function getMainClass()
    {
        return $this->hasOne(MainClass::className(), ['id' => 'main_class_id']);
    }

    /**
     * 关联频道
     * @return \yii\db\ActiveQuery
     */
    public function getOwnChannel($where = null)
    {
        $query = $this->hasMany(OttChannel::className(), ['sub_class_id' => 'id'])
                    ->orderBy([
                        /*'sub_class_id' => SORT_ASC,
                        'ott_channel.id' => SORT_ASC,*/
                        'sort' => SORT_ASC,
                    ]);

        if ($where) {
            $query->where($where);
        }

        return $query;
    }

    public function getUseText()
    {
        return $this->use_flag_text[$this->use_flag];
    }

    public function beforeDelete()
    {
        $ownChannel = $this->ownChannel;
        foreach ($ownChannel as $channel) {
            $channel->delete();
        }
        return true;
    }
}
