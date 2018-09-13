<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OttAccess */

$this->title = 'Create Ott Access';
$this->params['breadcrumbs'][] = ['label' => 'Ott Accesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-access-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
