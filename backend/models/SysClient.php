<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sys_client".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $phone 手机
 * @property int $admin_id
 * @property int $client_age 名称
 * @property string $client_address 地址
 * @property string $client_email 邮箱
 * @property string $client_qq qq
 * @property string $client_engname 英文名
 */
class SysClient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['admin_id', 'client_age'], 'integer'],
            [['name', 'client_address', 'client_email', 'client_engname'], 'string', 'max' => 100],
            [['phone', 'client_qq'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('backend', 'Name'),
            'phone' => Yii::t('backend', 'Phone'),
            'admin_id' => Yii::t('backend', 'Associated Account'),

            'client_address' => Yii::t('backend', 'Address'),
            'client_email' => Yii::t('backend', 'Mail'),
            'client_qq' => Yii::t('backend', 'QQ'),
            'client_engname' => Yii::t('backend', 'English name'),
        ];
    }

    /**
     * 关联帐号
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Admin::className(), ['id' => 'admin_id']);
    }

    /**
     * 获取id-name 关联数组数据
     * @return array
     */
    public static function getAll()
    {
        $data = self::find()->select('id,name')->asArray()->all();
        if (empty($data)) {
            return [];
        }
        return ArrayHelper::map($data, 'id' , 'name');
    }

}
