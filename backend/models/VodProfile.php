<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "iptv_vod_profile".
 *
 * @property int $id
 * @property string $name
 * @property string $profile
 */
class VodProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iptv_vod_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile'], 'string'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'profile' => 'Profile',
        ];
    }

    public static function findByName($name)
    {
        $profile = self::find()->where(['name' => $name])->one();

        if ($profile) {
            return json_decode($profile->profile, true);
        }

        return false;
    }
}
