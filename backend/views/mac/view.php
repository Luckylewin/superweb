<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mac */

$this->title = $model->MAC;
$this->params['breadcrumbs'][] = ['label' => 'Macs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mac-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'MAC',
            'SN',

            'ver',
            'regtime',
            'logintime',
            'duetime',
            [
                'attribute' => 'use_flag',
                'value' => function($model) {
                    return $model->getUseFlag($model->use_flag);
                }
            ],
            'contract_time',
            'access_token',
            'access_token_expire:datetime'
        ],
    ]) ?>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->MAC], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->MAC], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('backend','Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
    </p>

</div>
