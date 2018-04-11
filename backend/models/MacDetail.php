<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mac_detail".
 *
 * @property string $MAC mac地址
 * @property string $name 用户名
 * @property string $user_name
 * @property string $telephone 手机
 * @property string $addr 地址
 * @property string $ip_addr ip地址
 * @property int $ssh_port ssh端口
 * @property int $web_port web端口
 * @property int $warn_level 警告等级
 * @property string $email 邮箱
 * @property int $client_id 客户id
 */
class MacDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mac_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MAC'], 'required'],
            [['ssh_port', 'web_port', 'warn_level', 'client_id'], 'integer'],
            [['MAC', 'name'], 'string', 'max' => 64],
            [['user_name', 'telephone', 'ip_addr'], 'string', 'max' => 32],
            [['addr', 'email'], 'string', 'max' => 256],
            [['MAC'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MAC' => 'mac地址',
            'name' => '用户名',
            'user_name' => 'User Name',
            'telephone' => '手机',
            'addr' => '地址',
            'ip_addr' => 'ip地址',
            'ssh_port' => 'ssh端口',
            'web_port' => 'web端口',
            'warn_level' => '警告等级',
            'email' => '邮箱',
            'client_id' => '客户id',
        ];
    }

    public function getClient()
    {
        return $this->hasOne(IptvClient::className(), ['ID' => 'client_id']);
    }

}
