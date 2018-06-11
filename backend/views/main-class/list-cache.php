<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '缓存列表';
$this->params['breadcrumbs'][] = ['label' => '分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vodlink-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'key_name',
            [
                'class' => 'common\grid\MyActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('查看XML', Url::to(['main-class/view-cache', 'key' => $model['key_name']]), [
                            'class' => 'btn btn-info btn-xs',
                            'target' => '_blank'
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>

</div>

<?= Html::a('返回', ['index'], ['class' => 'btn btn-default']) ?>
