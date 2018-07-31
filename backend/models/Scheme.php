<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/10
 * Time: 19:39
 */

namespace backend\models;


use Yii;

/**
 * This is the model class for table "iptv_scheme".
 *
 * @property int $id
 * @property string $schemeName
 * @property string $cpu
 * @property string $flash
 * @property string $ddr
 */
class Scheme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_scheme';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cpu', 'flash'], 'required'],
            [['schemeName', 'cpu', 'flash', 'ddr'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'schemeName' => Yii::t('backend', 'Name'),
            'cpu' => 'CPU',
            'flash' => 'Flash',
            'ddr' => 'DDR',
        ];
    }

    public static function getAll()
    {
        return self::find()->asArray()->all();
    }

}
