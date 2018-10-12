<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\KaraokeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Karaoke video');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-name-karaoke-index">

    <?php //$this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered'],
        'pager' => [
            'class' => 'common\widgets\goPager',
            'go' => true,
            'maxButtonCount' => 15
        ],
        'layout' => "\n{items}{summary}\n{pager}\n",
        'columns' => [
            [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => ['style' => 'width:50px;'
                    ]
            ],
            [
                'attribute' => 'albumName',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->albumName,null, [
                        'class' => 'detail',
                        'data-toggle' => 'modal',
                        'data-target' =>"#show-modal",
                        'data-id' => $model->ID,
                        'data-name' => $model->albumName,
                        'data-image' => $model->image,
                        'data-mainActor' => $model->mainActor,
                        'data-info' => mb_substr($model->info, 0 , 120) . '...',
                        'data-tags' => $model->tags,
                        'data-area' => $model->area,
                        'data-hit_count' => $model->hit_count . '℃',
                        'data-source' => $model->source,
                        'data-year' => $model->updatetime,
                        'data-turl' => $model->trueUrl,
                        'data-url' => $model->url,
                    ]);
                },
                'headerOptions' => ['class' => 'col-md-6' ]
            ],

            [
                    'attribute' => 'url',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function($model) {
                        if ($model->source == 'Youtube') {
                            return Html::a($model->url, "https://www.youtube.com/watch?v=" . $model->url, [
                                'target' => '_blank'
                            ]);
                        } else {
                            return Html::a($model->url, \common\components\Func::getAccessUrl($model->url), [
                                'target' => '_blank'
                            ]);
                        }
                    }
            ],

            [
                    'attribute' => 'area',
                    'filter' => \backend\models\Karaoke::getLang(),
                    'options' => ['style' => 'width:70px;']

            ],

            [
                'attribute' => 'hit_count',
                'filter' => false,
                'label' => Yii::t('backend', 'heat'),
                'options' => ['style' => 'width:70px;']
            ],

         /*   [
                    'attribute' => 'is_del',
                    'filter' => \backend\models\Karaoke::$delStatus,
                    'options' => ['style' => 'width:100px;'],
                    'value' => function($model) {
                        return $model->status;
                    }
            ],*/


            [
                'class' => 'common\grid\MyActionColumn',
                'template' => '{update} {delete}',
                'size' => 'btn-sm',
                'header' => Yii::t('backend', 'Operation')
            ],



            //'directors',
            //'tags',
            //'info:ntext',
            //'area',
            //'keywords',
            //'wflag',
            //'year',
            //'mod_version',
            //'updatetime',
            //'totalDuration',
            //'flag',

            //'voole_id',
            //'price',
            //'is_finish',
            //'yesterday_viewed',
            //,
            //'url:url',
            //'act_img',
            //'download_flag',
        ],
    ]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>

<?php

Modal::begin([
    'id' => 'show-modal',
    'size' => Modal::SIZE_LARGE,
    'header' => '<h4 class="modal-title">详情</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a>',
]);

echo $html =<<<HTML
<table class="table table-striped table-bordered detail-view">
<tbody>
<tr><th>ID</th><td id="id"></td></tr>
<tr><th>标题</th><td id="name"></td></tr>
<tr><th>封面</th><td><img id="image" src="https://i.ytimg.com/vi/LRfgq9HdOd4/hqdefault.jpg" width="100" alt=""></td></tr>
<tr><th>描述</th><td style="font-size: 3px;word-break: break-all" id="info"></td></tr>
<tr><th>语言</th><td id="area"></td></tr>
<tr><th>时间</th><td id="year"></td></tr>
<tr><th>热度</th><td id="hit-count">0</td></tr>
<tr><th>来源</th><td id="source">upload</td></tr>
<tr><th>Url</th><td><a id="url" href="#" target="_blank">LRfgq9HdOd4</a></td></tr>
</tbody></table>
HTML;


Modal::end();

$requestUrl = \yii\helpers\Url::to(['client/bind-account']);
$requestJs=<<<JS
     $(document).on('click', '.detail', function() {
       $('#id').text($(this).data('id'));
       $('#name').text($(this).data('name'));
       $('#image').attr('src', $(this).data('image'));
       $('#mainActor').text($(this).data('mainActor'));
      
       $('#info').text($(this).data('info'));
       $('#area').text($(this).data('area'));
       $('#hit-count').text($(this).data('hit_count'));
       $('#source').text($(this).data('source'));
       $('#year').text($(this).data('year'));
       $('#url').attr('href',$(this).data('turl')).text($(this).data('url'));
     
     })
JS;
$this->registerJs($requestJs);

?>
