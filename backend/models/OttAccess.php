<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ott_access".
 *
 * @property string $mac mac地址
 * @property string $genre 列表名称
 * @property int $is_valid 是否有权限
 * @property string $deny_msg 拒绝原因
 * @property int $expire_time 过期时间
 */
class OttAccess extends \yii\db\ActiveRecord
{

    public static function primaryKey()
    {
        return ['mac', 'genre'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ott_access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_valid', 'expire_time'], 'integer'],
            [['mac', 'deny_msg'], 'string', 'max' => 50],
            [['genre'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mac' => 'mac地址',
            'genre' => '列表名称',
            'is_valid' => '是否有权限',
            'deny_msg' => '拒绝原因',
            'expire_time' => '过期时间',
        ];
    }
}
