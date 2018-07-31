<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RenewalCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Renewal Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renewal-card-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Renewal Card', ['renewal-card/batch-create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php if (Yii::$app->request->get('batch_id') == false): ?>

    <?php \yii\widgets\Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'batch',
            'card_num',
            'card_contracttime',
            'created_time:datetime',
            [
                'class' => 'common\grid\MyActionColumn',
                'template' => '{see}',
                'buttons' => [
                    'see' => function($url, $model ) {
                        return Html::a('查看', \yii\helpers\Url::to(['renewal-card/index', 'batch_id' => $model->batch]), [
                            'class' => 'btn btn-sm btn-info'
                        ]);
                    }
                ]
            ]
        ],
    ]); ?>


    <?php \yii\widgets\Pjax::end() ?>

    <?php else: ?>

        <?php \yii\widgets\Pjax::begin() ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'batch',
                'card_num',
                'card_secret',
                'card_contracttime',
                [
                        'attribute' => 'is_valid',
                        'value' => function($model) {
                            return $model->is_valid ? '有效' : '已核销';
                        }
                ],

                'created_time:datetime',
                //'updated_time:datetime',
                //'who_use',
                [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{delete}',
                ],

            ],
        ]); ?>

        <?= Html::a(Yii::t('backend','Go Back'), ['renewal-card/index'], ['class' => 'btn btn-default']) ?>


    <?php endif; ?>



</div>


