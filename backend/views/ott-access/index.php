<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ott Accesses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ott-access-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ott Access', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm'
            ],
        ],
    ]); ?>
</div>
