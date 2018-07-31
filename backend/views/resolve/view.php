<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\IptvUrlResolution */

$this->title = $model->method;
$this->params['breadcrumbs'][] = ['label' => '正则表达式列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iptv-url-resolution-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'method',
            [
                'attribute' => 'c',
                'format' => 'raw',
                'value' => function($data) {
                    $str = '';
                    $c = json_decode($data['c'], true);
                    foreach($c['regex'] as $key => $value) {
                        if (!empty($value)) {
                            $str .= "<span class='label label-primary'>{$key}</span>  ";
                            foreach($value as $k => $v) {
                                $str .= "<span class='label label-success'>". $v . "</span>   ";
                            }
                            $str .= '</br>';
                        }
                    }
                    return $str;
                }
            ],
            [
                'attribute' => 'android',
                'format' => 'raw',
                'value' => function($data) {
                    $str = '';
                    $c = json_decode($data['android'], true);
                    foreach($c['regex'] as $key => $value) {
                        if (!empty($value)) {
                            $str .= "<span class='label label-primary'>{$key}</span>  ";
                            foreach($value as $k => $v) {
                                $str .= "<span class='label label-success'>". $v . "</span>   ";
                            }
                            $str .= '</br>';
                        }
                    }
                    return $str;
                }
            ],
            'referer',
            'expire_time',
            'url:url',
        ],
    ]) ?>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定要删除该项吗',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('backend','Go Back'), \yii\helpers\Url::to(['resolve/index']),['class' => 'btn btn-default']) ?>
    </p>

</div>
