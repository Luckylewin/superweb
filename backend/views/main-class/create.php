<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MainClass */

$this->title = '创建分类';

?>
<div class="main-class-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
