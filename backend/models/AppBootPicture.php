<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "app_boot_picture".
 *
 * @property int $id
 * @property string $boot_pic 启动页面图片
 * @property string $link 链接
 * @property string $during 时间范围
 * @property string $status 状态(0无效1有效)
 * @property string $sort 排序
 * @property int $created_at 创建日期
 */
class AppBootPicture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_boot_picture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['boot_pic'], 'required'],
            [['created_at'], 'integer'],
            [['boot_pic', 'link', 'during', 'status', 'sort'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'boot_pic' => '启动页面图片',
            'link' => '链接',
            'during' => '时间范围',
            'status' => '状态', //(0无效1有效)
            'sort' => '排序',
            'created_at' => '创建日期',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => time()
            ]
        ];
    }
}
