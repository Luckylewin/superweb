<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\VodProfiles */

$this->title = Yii::t('backend', 'Create Vod Profiles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Vod Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-profiles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
