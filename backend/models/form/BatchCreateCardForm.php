<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/7/26
 * Time: 15:16
 */

namespace backend\models\form;

use backend\blocks\RenewCardBlock;
use Yii;
use backend\models\RenewalCard;
use yii\base\Model;


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

    public function getRandomString()
    {
        $str = '';
        for ($i = 0; $i <= 15; $i++) {
            $str .= mt_rand(0,9);
        }

        return $str;
    }

    //10000000 年卡
    public function save()
    {
        $this->validate();

        $model = new RenewalCard();
        $total = $this->num;
        $type = $this->type;

        //查询最大 批次
        $batch = RenewalCard::find()->max('batch');
        $max_card_num = RenewalCard::find()->max('card_num');

        if (empty($batch)) {
            $batch = 1;
            $max_card_num = '10000000';
        } else {
            $batch += 1;
            $max_card_num += 1;
        }

        for ($i=0; $i < $total; $i++) {
            $card_num = $max_card_num;
            $card_secret = $this->getRandomString();

            $exist = RenewalCard::find()->where(['card_secret' => $max_card_num])->exists();

            if ($exist == false) {
                $_model = clone $model;
                $_model->card_num = $card_num;
                $_model->card_secret = $card_secret;
                $_model->card_contracttime = $type;
                $_model->batch = $batch;
                $_model->is_valid = '1';
                $_model->save(false);
            } else {
                $i -= 1;
            }

            $max_card_num ++;
        }

        return true;

    }

}