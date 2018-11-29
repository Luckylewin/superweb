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
                    'action' => \yii\helpers\Url::to(['renewal-card/query']),
                    'method' => 'get'
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

                    return sprintf('%.1f', ($used/$total)*100) .'%';
                }
            ],
            [
                'class' => 'common\grid\MyActionColumn',
                'template' => '{see} {delete}',
                'buttons' => [
                    'see' => function($url, $model ) {
                        return Html::a(Yii::t('backend', 'View'), \yii\helpers\Url::to(['renewal-card/batch', 'batch_id' => $model->batch]), [
                            'class' => 'btn btn-sm btn-info'
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('backend', 'Delete'), \yii\helpers\Url::to(['renewal-card/batch-delete', 'batch_id' => $model->batch]), [
                            'class' => 'btn btn-danger'
                        ]);
                    }
                ]
            ]
        ],
    ]); ?>

    <?php \yii\widgets\Pjax::end() ?>

</div>

<?php if(Yii::$app->user->can('renewal-card/batch-create')): ?>
    <p>
        <?= Html::a(Yii::t('backend', 'Create Renewal Card'), ['renewal-card/batch-create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php endif; ?>