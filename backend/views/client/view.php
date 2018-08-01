<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SysClient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sys Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'phone',
            'admin_id',
            'client_age',
            'client_address',
            'client_email:email',
            'client_qq',
            'client_engname',
        ],
    ]) ?>

</div>
