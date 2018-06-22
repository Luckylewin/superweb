<?php


use \yii\bootstrap\Modal;


/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/6/22
 * Time: 16:09
 */

?>

<div>
    <?php
    Modal::begin([
        'id' => 'auth',
        'size' => Modal::SIZE_SMALL,
        'header' => '<h4 class="modal-title">输入操作码</h4>',
        'footer' => '',
        'options' => ['tabindex' => 1]
    ]);

    \yii\widgets\ActiveForm::begin();
    echo "<div class='form-group'>";
    echo \yii\helpers\Html::label('操作码','password');
    echo \yii\helpers\Html::input('password','password',null, [
        'class' => 'form-control',
        'id' => 'password',
        'autocomplete' =>"off"
    ]);
    echo "</div>";

    echo "<div class='form-group'>";
    echo \yii\helpers\Html::submitButton('提交', ['class' => 'btn-primary btn']);
    echo "</div>";
    \yii\widgets\ActiveForm::end();

    Modal::end();
    ?>
</div>

<?php

$JS =<<<JS
     $(function() {
      $('#auth').modal();
    })
JS;

$this->registerJs($JS);

?>
