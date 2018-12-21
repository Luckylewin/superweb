<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use \common\widgets\multilang\MultiLangWidget;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerJsFile('/statics/themes/default-admin/plugins/layer/layer.min.js', ['depends' => 'yii\web\JqueryAsset']);

?>
<div class="iptv-type-index">

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create','vod_list_id' => $list->list_id], ['class' => 'btn btn-success']) ?>

        <?= Html::a(Yii::t('backend', '同步'), ['sync','vod_list_id' => $list->list_id], ['class' => 'btn btn-info']) ?>

        <?= Html::a(Yii::t('backend', 'Language setting'), Url::to(['iptv-type/set-language', 'id' => Yii::$app->request->get('list_id')]),['class' => 'btn btn-primary '])?>

        <?= Html::a(Yii::t('backend', 'Baidu Translate'), Url::to(['iptv-type/translate', 'id' => Yii::$app->request->get('list_id')]),['class' => 'btn btn-primary '])?>

        <?= Html::a(Yii::t('backend','Go Back'), ['vod-list/index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function($model) {
                        return MultiLangWidget::widget([
                            'id'     => $model->id,
                            'origin' => $model->name,
                            'table' => \backend\models\IptvType::tableName(),
                            'field' => 'name',
                            'name'  => $model->name,
                            'options' => [
                                    'style' => 'margin:2px;',
                                    'class' => 'btn btn-default',
                                    'title' => $model->field
                            ]
                        ]);

                    }
            ],

            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img(\common\components\Func::getAccessUrl($model->image),[
                            'width' => '30'
                    ]);
                }
            ],
            [
                    'label' => Yii::t('backend', 'options'),
                    'format' => 'raw',
                    'options' => ['style' => 'width:58%'],
                    'value' => function($model) {
                        $data = \backend\models\IptvTypeItem::getTypeItems($model->id);
                        $str = '';

                        foreach ($data as $key => $item) {
                            if ($item->exist_num && $item->is_show) {
                                $str .= MultiLangWidget::widget([
                                    'id'     => $item->id,
                                    'origin' => $item->name,
                                    'table'  => \backend\models\IptvTypeItem::tableName(),
                                    'field'  => 'name',
                                    'name'   => $item->name,
                                    'options' => [ 'style' => 'margin:2px;']
                                ]);

                            } else {
                                $str .= Html::button($item->name , [
                                    'title' => $item->exist_num,
                                    'class' => 'btn btn-default',
                                    'style' => 'margin: 2px;background: #eee'
                                ]);
                            }
                        }

                        $str .= \common\widgets\frameButton::widget([
                                'url' => Url::to(['type-item/create', 'type_id' => $model->id]),
                                'content' => '',
                                'icon' => 'fa-plus',
                                'options' => ['class' => 'btn btn-success']
                        ]);

                        return $str;
                    }
            ],

            [
                    'class' => 'common\grid\MyActionColumn',
                    'size' => 'btn-sm',
                    'template' => '{sub} {update} {delete}',
                    'buttons' => [
                            'sub' => function($url, $model, $key) {
                                return Html::a(Yii::t('backend', 'List'), Url::to(['type-item/index', 'type_id' => $model->id, 'list_id' => Yii::$app->request->get('list_id')]),[
                                        'class' => 'btn btn-sm btn-info'
                                ]);
                            },

                    ]
            ],
        ],
    ]); ?>
</div>


?>