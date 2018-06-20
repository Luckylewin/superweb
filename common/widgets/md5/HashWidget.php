<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/6/20
 * Time: 10:09
 */

namespace common\widgets\md5;


use yii\bootstrap\Widget;

/**
 *
 *  <?= HashWidget::widget([
                'model' => $model,
                'form' => $form,
                'field => 'md5'
    ]); ?>
 *
 *
 * 哈希md5值
 * Class HashWidget
 * @package common\widgets\md5
 */
class HashWidget extends Widget
{
    public $model;
    public $form;
    public $field;

    public function run()
    {
       return $this->render('index', [
           'model' => $this->model,
           'form' => $this->form,
           'field' => $this->field
       ]);
    }
}