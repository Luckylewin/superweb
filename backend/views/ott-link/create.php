<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OttLink */

$this->title = 'Create Ott Link';
$this->params['breadcrumbs'][] = ['label' => 'Ott Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
    'schemes' => $schemes
]) ?>