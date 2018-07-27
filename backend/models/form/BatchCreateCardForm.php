<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/26
 * Time: 15:16
 */

namespace backend\models\form;


use backend\models\Config;
use yii\base\Model;
use yii\helpers\Json;

class BatchCreateCardForm extends Model
{
    public $type;
    public $num;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'num'], 'required'],
            ['num', 'integer', 'min'=>1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => '类型',
            'num' => '数量'
        ];
    }

    public function save()
    {
        $this->validate();

        return false;
    }

}