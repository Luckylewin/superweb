<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "app_locker_switcher".
 *
 * @property string $mac mac地址
 * @property string $app_name app名称
 * @property string $switch 开关:on|off
 */
class AppLocker extends \yii\db\ActiveRecord
{
    public static function primaryKey($asArray = false)
    {
        return ['mac', 'app_name'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_locker_switcher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mac'], 'string', 'max' => 32],
            [['app_name'], 'string', 'max' => 20],
            [['switch'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mac' => 'mac地址',
            'app_name' => 'app名称',
            'switch' => '开关:on|off',
        ];
    }
}
