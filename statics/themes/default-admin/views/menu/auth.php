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
        'header' => '<h4 class="modal-title">'. Yii::t('backend', 'Please Input Operate Code').'</h4>',
        'footer' => '',
        'options' => ['tabindex' => 1]
    ]);

    \yii\widgets\ActiveForm::begin([
            'method' => 'POST'
    ]);
    echo "<div class='form-group'>";
    echo \yii\helpers\Html::label(Yii::t('backend', 'Operate Code'),'password');
    echo \yii\helpers\Html::input('password','password',null, [
        'class' => 'form-control',
        'id' => 'password',
    ]);
    echo "</div>";

    echo "<div class='form-group'>";
    echo \yii\helpers\Html::submitButton(\Yii::t('backend','Submit'), ['class' => 'btn-primary btn']);
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
