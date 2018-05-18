<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OttLink */

$this->title = 'Create Ott Link';
$this->params['breadcrumbs'][] = ['label' => 'Ott Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
