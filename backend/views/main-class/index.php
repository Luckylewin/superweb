<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use \yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Main Classes';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .grid-view td{
        text-align: center;
    }
</style>

<div class="main-class-index">
    <p>
        <?= Html::a('创建分类', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量导入', ['sub-class/import-via-text','mode' => 'mainClass'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-hover table-bordered'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'zh_name',

            [
                'attribute' => 'sort',
                'options' => ['style' => 'width:70px;'],
                'format' => 'raw',
                'value' => function($model) {
                    return \yii\bootstrap\Html::textInput('sort', $model->sort, [
                        'class' => 'form-control change-sort',
                        'data-id' => $model->id,
                        'old-value' => $model->sort
                    ]);
                }
            ],
            [
                'label' => '列表版本',
                'format' => 'raw',
                'value' => function($model) {
                    $version = (new \backend\models\Cache())->getCacheVersion($model->name);
                    if ($version) {
                        return Html::a($version,Url::to(['main-class/list-cache', 'id' => $model->id]), [
                                'class' => 'btn btn-link'
                        ]);
                    }
                    return '';
                }
            ],
            //'icon',
            //'icon_bg',

            [
                'class'=> 'common\grid\MyActionColumn',
                'options' => ['style' => 'width:260px;'],
                'template' => '{next}&nbsp;&nbsp;| &nbsp;&nbsp;{create-cache} {view} {update} {delete}',
                'buttons' => [
                        'next' => function($url ,$model) {
                            return Html::a('&nbsp;&nbsp;<i class="glyphicon glyphicon-th-list"></i>&nbsp;&nbsp;', ['sub-class/index', 'main-id' => $model->id], [
                                'class' => 'btn btn-success btn-xs'
                            ]);
                        },
                        'create-cache' => function($url, $model) {
                            return Html::a('生成缓存', '#', [
                                'url' => Url::to(['sub-class/generate-cache', 'id' => $model->id]),
                                'class' => 'btn btn-primary btn-xs create-cache',
                                'id' => 'cache-btn',
                                'data-toggle' => 'modal',
                                'data-target' => '#operate-modal',
                            ]);
                        }
                ]
            ]


        ],
    ]); ?>

    
</div>

<?php

Modal::begin([
    'id' => 'operate-modal',
    'size' => Modal::SIZE_SMALL,
    'header' => '<h4 class="modal-title">操作提示</h4>',
    'footer' => '',
]);
echo "<h4><i class='fa fa-spinner fa-pulse'> </i> 生成缓存中</h4>";
Modal::end();

?>


<?php

$updateUrl = Url::to(['main-class/update', 'field' => 'sort', 'id'=>'']);
$csrfToken = Yii::$app->request->csrfToken;

$requestJs=<<<JS
    $('.create-cache').click(function(){
        var url = $(this).attr('url');
        setTimeout(function(){
            window.location.href = url;
        },200);
    });
    $('.change-sort').blur(function(){
        var newValue = $(this).val();
        var oldValue = $(this).attr('value');
        
        var id = $(this).attr('data-id');
        var url = '{$updateUrl}' + id;
       
        if (newValue === oldValue) return false;
        
        $.post(url, {sort:newValue,_csrf:'{$csrfToken}'}, function(data){
              window.location.reload();
        })
    });

JS;

$this->registerJs($requestJs);

?>
