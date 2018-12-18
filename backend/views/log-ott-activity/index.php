<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\LogOttActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log Ott Activities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-ott-activity-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{summary}{pager}',
        'pager' => [
            'class' => 'common\widgets\goPager',
            'firstPageLabel' => Yii::t('backend', 'First Page'),
            'lastPageLabel' => Yii::t('backend', 'Last Page'),
            'go' => true
        ],
        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'timestamp',
                'filter' => false,
                'format' => 'datetime'
            ],

            [
                'attribute' => 'genre',
                'filter' => Html::dropDownList('',null,\yii\helpers\ArrayHelper::map(\common\models\MainClass::find()->where(['is_log' => 1])->all(), 'list_name','list_name'), [
                    'class' => 'form-control'
                ])
            ],


            'mac',

            [
                    'attribute' => 'code',
                    'filter' => Html::dropDownList('',null,['0' => '成功下载', '16' => '版本一致', '17' => '没有数据','36' => '没有权限'], [
                            'class' => 'form-control'
                    ])
            ],
        ],
    ]); ?>
</div>
