<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Mac */


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
            'is_online',
            'ver',
            'regtime',
            'logintime',
            'duetime',
            [
                'attribute' => 'use_flag',

                'value' => function($model) {
                    /**
                     * @var $model \backend\models\Mac
                     */
                    return $model->getUseFlag($model->use_flag);
                }
            ],
            'contract_time',
            'access_token',
            'access_token_expire:datetime'
        ],
    ]) ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->MAC], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend','Go Back'), Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
    </p>

</div>
