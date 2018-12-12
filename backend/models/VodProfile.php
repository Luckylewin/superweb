<?php

namespace backend\models;

/**
 * This is the model class for table "iptv_vod_profile".
 *
 * @property int $id
 * @property string $name
 * @property string $profile
 * @property string $language
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
            ['profile', 'safe'],
            ['profile', 'default', 'value' => 'zh-US']
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

    public static function findByName($name, $language = 'en-US')
    {
        $profile = self::find()->where(['name' => $name, 'language' => $language])->one();

        if ($profile) {
            return json_decode($profile->profile, true);
        }

        return false;
    }
}
