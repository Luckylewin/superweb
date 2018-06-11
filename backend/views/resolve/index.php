<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '本地解析正则表达式列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-url-resolution-index">



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'method',
            'referer',
            'expire_time',
            'url:url',
            [
                    'class' => 'common\grid\MyActionColumn'
            ],
        ],
    ]); ?>

    <p>
        <?= Html::a('新增解析', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a("生成缓存($version)", ['resolve/cache'], ['class' => 'btn btn-default']) ?>
    </p>

</div>
