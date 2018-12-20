<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ParadeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'EPG');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parade-index">


    <p>
        <?= common\widgets\frameButton::widget([
                'url' => Url::to(['parade/create']),
                'content' => Yii::t('backend', 'Create'),
                'icon' => 'fa-edit',
                'options' => ['class' => 'btn btn-success']
        ]) ?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?= Html::a(Yii::t('backend', 'Generate Cache'), ['create-cache'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('backend', 'Clear Cache'), ['clear-cache'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php

    try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'class' => 'common\widgets\goPager',
                'go' => true
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'channel_name',
                    'contentOptions' => ['class' => 'col-md-1'],
                    'format' => 'raw',
                    'value' => function($model) {
                            return common\widgets\frameButton::widget([
                                'url' => \yii\helpers\Url::to(['parade/list-channel','name'=>$model->channel_name]),
                                'content' => $model->channel_name
                            ]);
                    }
                ],

                [
                    'label' => Yii::t('backend', 'Associated Channel'),
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'col-md-8'],
                    'value' => function ($model) {
                        $channels = $model->channel;

                        if ($channels) {
                            $str = '';
                            foreach ($channels as $key => $channel) {
                                $str .= Html::a($channel['name'], \yii\helpers\Url::to(['ott-channel/view', 'id' => $channel['id']])) . '&nbsp;,';
                                if ($key != 0 && $key % 3 == 0) $str .= '<br/>';
                            }

                            return $str;
                        }
                        return '';
                    }
                ],


                [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'contentOptions' => ['class' => 'col-md-3'],
                    'buttons' => [
                        'view' => function($url, $model, $key) {
                            $title = Yii::t('backend', 'View');
                            return Html::a($title, \yii\helpers\Url::to(['parade/list-channel','name'=>$model->channel_name]),[
                                'class'=>'btn btn-info btn-sm',
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '0',
                            ]);
                        },
                        'delete' => function($url, $model, $key) {
                            $title = Yii::t('backend', 'Delete');
                            return Html::a($title, \yii\helpers\Url::to(['parade/batch-delete','name'=>$model->channel_name]),[
                                'class'=>'btn btn-danger btn-sm',
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '0',
                            ]);
                        },

                        'bind' => function($url, $model, $key) {
                            return Html::a(Yii::t('backend', 'Bind Channel'), null, [
                                'class' => 'btn btn-default btn-sm bind',
                                'data-toggle' => 'modal',
                                'data-target' => '#bind-modal',
                                'data-id' => $model->channel_name,
                            ]);
                        }
                    ],
                    'template' => '{bind}&nbsp;{view} &nbsp;{delete}',
                ],


            ],
        ]);
    } catch (\Exception $e) {

    }

    ?>
    <?php Pjax::end(); ?>
</div>


<?php

Modal::begin([
    'id' => 'bind-modal',
    'size' => Modal::SIZE_DEFAULT,
    'header' => '<h4 class="modal-title">'. Yii::t('backend', 'epg').'(<span id="channel_name"></span>)'. Yii::t('backend', 'Associated Channel').'</h4>',
    'footer' => '<a href="#" class="btn btn-default" data-dismiss="modal">'. Yii::t('backend', 'close').'</a>',
]);

$requestUrl = Url::to(['parade/global-search']);

$requestJs=<<<JS
     $(document).on('click', '.bind', function() {
                var id = $(this).attr('data-id');
                $('#channel_name').text(id);
                $.get('{$requestUrl}', {'id':id},
                    function (data) {
                        $('.modal-body').css('min-height', '70px').html(data);
                    }
           )
     });
JS;

$this->registerJs($requestJs);

Modal::end();
?>