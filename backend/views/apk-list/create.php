<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ApkList */

$this->params['breadcrumbs'][] = ['label' => 'Apk列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apk-list-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
