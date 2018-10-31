<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \backend\models\RenewalCard;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RenewalCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Renewal Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renewal-card-index">



    <div class="panel panel-default">
        <div class="panel-body">
            <div class="parade-search">
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'action' => ['index'],
                ]); ?>
                <?= $form->field($searchModel, 'card_secret') ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
                </div>
                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Renewal Card'), ['renewal-card/batch-create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php if (Yii::$app->request->get('batch_id') == false): ?>

    <?php \yii\widgets\Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                    'attribute' => 'batch',
                    'filter' => false,
                    'options' => ['style' => 'width:90px']
            ],

            'card_num',
            'card_contracttime',

            'created_time:datetime',
            [
                'label' => '使用量',
                'value' => function($model) {
                    $total = RenewalCard::find()->where(['batch' => $model->batch])->count();
                    $used  = RenewalCard::find()->where(['batch' => $model->batch, 'is_valid' => 0])->count();

                    return "{$used}/{$total}(". $used/$total*100 . '%)';
                }
            ],
            [
                'class' => 'common\grid\MyActionColumn',
                'template' => '{see}',
                'buttons' => [
                    'see' => function($url, $model ) {
                        return Html::a(Yii::t('backend', 'View'), \yii\helpers\Url::to(['renewal-card/index', 'batch_id' => $model->batch]), [
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

                [
                    'attribute' => 'batch',
                    'options' => ['style' => 'width:90px']
                ],
                'card_num',
                'card_secret',
                'card_contracttime',
                [
                        'attribute' => 'is_valid',
                        'format' => 'raw',
                        'value' => function($model) {
                            $class = $model->is_valid ? 'label label-success' : 'label label-default';
                            $content = $model->is_valid ? Yii::t('backend', 'Valid') : Yii::t('backend', 'Write off');
                            return Html::tag('span', $content, ['class' => $class]);
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


