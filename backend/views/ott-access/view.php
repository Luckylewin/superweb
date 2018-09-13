<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OttAccess */

$this->title = $model->mac;
$this->params['breadcrumbs'][] = ['label' => 'Ott Accesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-access-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'mac' => $model->mac, 'genre' => $model->genre], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'mac' => $model->mac, 'genre' => $model->genre], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mac',
            'genre',
            [
                'attribute' => 'is_valid',
                'format' => 'raw',
                'value' => function($model) {
                    $text = $model->is_valid ? Yii::t('backend', 'Yes') : Yii::t('backend', 'Yes');
                    $class = $model->is_valid ? ['class' => 'label label-success'] : ['class' => 'label label-default'];

                    return Html::tag('span', $text, $class);
                }
            ],
            'deny_msg',
            'expire_time:datetime',
        ],
    ]) ?>

</div>
