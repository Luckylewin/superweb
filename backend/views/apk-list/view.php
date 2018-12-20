<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ApkList */

$this->title = $model->typeName;
$this->params['breadcrumbs'][] = ['label' => 'APK列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apk-list-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'typeName',
            'type',
            'class',
            [
                'attribute' => 'img',
                'format' => ['image', ['width'=> 40]]
            ],
            'sort',
            [
                    'attribute' => 'scheme_id',
                    'visible' => Yii::$app->user->can('apk-list/set-scheme'),
                    'headerOptions' => ['class' => 'col-md-6'],
                    'format' => 'raw',
                    'value' => function($model) {
                        $schemes = $model->schemes;
                        $str = '';
                        foreach ($schemes as $key => $scheme) {
                            $str .= Html::button($scheme->schemeName ,[
                                    'class' => 'btn btn-default btn-xs',
                                    'style' => 'margin:2px 2px'
                            ]) . "&nbsp" . ($key > 0 && $key % 6 == 0 ? '<br/>' : '');
                        }
                        return $str;
                    }
            ],
        ],
    ]) ?>

</div>

<p>
    <?= Html::a('发布版本', ['apk-detail/create', 'id' => $model->ID], ['class' => 'btn btn-info']) ?>

    <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['apk-list/index']), ['class' => 'btn btn-default']) ?>
</p>
