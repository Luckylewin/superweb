<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/4/10
 * Time: 19:39
 */

namespace backend\models;


use Yii;
use yii\helpers\ArrayHelper;

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
            [['schemeName'], 'required'],
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

    public static function getOptions()
    {
        return ArrayHelper::map(self::getAll(), 'id', 'schemeName');
    }

    public static function getSupportedSchemeByNotIn($scheme_id)
    {
        return self::find()->where(['not in', 'id', $scheme_id])->select('id')->column();
    }

}
