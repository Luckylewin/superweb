<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ApkList */

$this->title = $model->typeName;
$this->params['breadcrumbs'][] = ['label' => 'APK列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apk-list-view">

    <h1><?= Html::encode($this->title) ?></h1>


<p>
   <?= Html::a('发布版本', ['apk-detail/create', 'id' => $model->ID], ['class' => 'btn btn-info']) ?>
</p>

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
                    'format' => 'raw',
                    'value' => function($data) {
                        $schemes = $data->scheme;
                        $str = '';
                        foreach ($schemes as $scheme) {
                            $str .= Html::label($scheme->schemeName, null ,[
                                    'class' => 'label label-default'
                            ]) . "&nbsp";
                        }
                        return $str;
                    }
            ],
        ],
    ]) ?>

</div>

<p>
    <?= Html::a('编辑', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('返回', \yii\helpers\Url::to(['apk-list/index']), ['class' => 'btn btn-default']) ?>
</p>
