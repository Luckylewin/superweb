<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '链接列表';
$this->params['breadcrumbs'][] = ['label' => '视频列表', 'url' => ['vod/index']];
$this->params['breadcrumbs'][] = $vod->vod_name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vodlink-index">

    <h1><?= Html::encode($vod->vod_name) ?></h1>


    <p>
        <?= Html::a('创建链接', Url::to(['link/create', 'vod_id'=>$vod->vod_id]), ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                "class" => "yii\grid\CheckboxColumn",
                "name" => "id",
            ],
            //'id',
            //'video_id',
            'url:url',
            //'hd_url:url',
            'episode',
            [
                    'class' => 'common\grid\MyActionColumn',
                    'template' => '{update} {delete}',
                    'buttons' => [
                            'view' => function($url, $model) {
                                 return Html::a('查看',Url::to(['link/view','vod_id'=>$model->video_id, 'id' => $model->id]),[
                                         'class' => 'btn btn-success btn-xs'
                                 ]);
                            }
                    ]
            ],
        ],
    ]); ?>

</div>

<?= Html::button("批量删除",[
    'class' => 'gridview btn btn-danger',
])?>

<?php

$batchDelete = Url::to(['link/batch-delete']);
$csrfToken = Yii::$app->request->csrfToken;

$registerJs=<<<JS
    $(document).on('click', '.gridview', function () {
         var keys = $("#grid").yiiGridView("getSelectedRows");
         $.post('{$batchDelete}', {id:keys,_csrf:'{$csrfToken}'}, function(data) {
              window.location.reload(); 
         });
    });
JS;

$this->registerJs($registerJs);

?>
