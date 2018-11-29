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

</div>


