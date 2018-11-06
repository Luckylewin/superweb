<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/6
 * Time: 15:05
 */

namespace backend\models\form;


use yii\base\Model;

class LogChangedForm extends Model
{
    public $month;

    public $year;

    public function rules()
    {
        return [
            [['month', 'year'], 'required'],
            [['month', 'year'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'month' => '月份',
            'year'  => '年份'
        ];
    }

}