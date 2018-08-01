<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\models\User;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'email:email',
            //'status',
            'created_at:date',
            //'updated_at',
            //'access_token',
            //'access_token_expire',
            //'allowance',
            //'allowance_updated_at',
            [
                    'attribute' => 'identity_type',
                    'filter' => User::getVipType(),
                    'filterInputOptions' => [
                            'prompt' => Yii::t('backend', 'All'),
                            'class' => 'form-control'
                    ],
                    'value' => function($model) {
                        return Yii::t('backend', User::$vipType[$model->identity_type]);
                    }
            ],

            [
               'class' => 'common\grid\MyActionColumn',
                'size' => 'btn-sm',
                'template' => '{view} {delete}'
            ],
        ],
    ]); ?>
</div>
