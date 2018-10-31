<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Vod */

$this->title = $model->vod_name;
$this->params['breadcrumbs'][] = ['label' => 'Vods', 'url' => Yii::$app->request->referrer];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vod-view">

    <h4><?= Html::encode($this->title) ?></h4>

    <table class="table table-bordered">

        <tbody>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_id') ?></th>
            <td> <?= $model->vod_id ?></td>
            <th> <?= $model->getAttributeLabel('vod_name') ?></th>
            <td> <?= $model->vod_name ?></td>
            <th> <?= $model->getAttributeLabel('vod_ename') ?></th>
            <td> <?= $model->vod_ename ?></td>
        </tr>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_title') ?></th>
            <td> <?= $model->vod_title ?></td>
            <th> <?= $model->getAttributeLabel('vod_type') ?></th>
            <td> <?= $model->vod_type ?></td>
            <th> <?= $model->getAttributeLabel('vod_keywords') ?></th>
            <td> <?= $model->vod_keywords ?></td>
        </tr>

        <tr>
            <th> <?= $model->getAttributeLabel('vod_actor') ?></th>
            <td> <?= $model->vod_actor ?></td>
            <th> <?= $model->getAttributeLabel('vod_director') ?></th>
            <td> <?= $model->vod_director ?></td>
            <th> <?= $model->getAttributeLabel('vod_area') ?></th>
            <td> <?= $model->vod_area ?></td>
        </tr>

        <tr>
            <th> <?= $model->getAttributeLabel('vod_language') ?></th>
            <td> <?= $model->vod_language ?></td>
            <th> <?= $model->getAttributeLabel('vod_year') ?></th>
            <td> <?= $model->vod_year ?></td>
            <th> <?= $model->getAttributeLabel('vod_continu') ?></th>
            <td> <?= $model->vod_continu ?></td>
        </tr>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_total') ?></th>
            <td> <?= $model->vod_total ?></td>
            <th> <?= $model->getAttributeLabel('vod_isend') ?></th>
            <td> <?= $model->vod_isend ?></td>
            <th> <?= $model->getAttributeLabel('vod_hits') ?></th>
            <td> <?= $model->vod_hits ?></td>
        </tr>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_hits_day') ?></th>
            <td> <?= $model->vod_hits_day ?></td>
            <th> <?= $model->getAttributeLabel('vod_hits_week') ?></th>
            <td> <?= $model->vod_hits_week ?></td>
            <th> <?= $model->getAttributeLabel('vod_hits_month') ?></th>
            <td> <?= $model->vod_hits_month ?></td>
        </tr>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_stars') ?></th>
            <td> <?= $model->getStar(); ?></td>
            <th> <?= $model->getAttributeLabel('vod_status') ?></th>
            <td> <?= $model->vod_status ?></td>
            <th> <?= $model->getAttributeLabel('vod_price') ?></th>
            <td> <?= $model->vod_price ?></td>
        </tr>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_letter') ?></th>
            <td> <?= $model->vod_letter ?></td>
            <th> <?= $model->getAttributeLabel('vod_gold') ?></th>
            <td> <?= $model->vod_gold ?></td>
            <th> <?= $model->getAttributeLabel('vod_golder') ?></th>
            <td> <?= $model->vod_golder ?></td>
        </tr>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_length') ?></th>
            <td> <?= $model->vod_length ?></td>
            <th> <?= $model->getAttributeLabel('vod_gold') ?></th>
            <td> <?= $model->vod_gold ?></td>
            <th> <?= $model->getAttributeLabel('vod_golder') ?></th>
            <td> <?= $model->vod_golder ?></td>
        </tr>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_multiple') ?></th>
            <td> <?= $model->vod_multiple ?></td>
            <th> <?= $model->getAttributeLabel('vod_state') ?></th>
            <td> <?= $model->vod_state ?></td>
            <th> <?= $model->getAttributeLabel('vod_version') ?></th>
            <td> <?= $model->vod_version ?></td>
        </tr>
        <tr>
            <th> <?= $model->getAttributeLabel('vod_douban_id') ?></th>
            <td> <?= $model->vod_douban_id ?></td>
            <th> <?= $model->getAttributeLabel('vod_douban_score') ?></th>
            <td> <?= $model->vod_douban_score ?></td>
            <th> <?= $model->getAttributeLabel('vod_scenario') ?></th>
            <td> <?= $model->vod_scenario ?></td>
        </tr>
        <tr>
            <th><?= $model->getAttributeLabel('vod_content') ?></th>
            <td colspan="5">
                <?= $model->vod_content ?>
            </td>
        </tr>

        </tbody>
    </table>



    <?php
   /* DetailView::widget([
        'model' => $model,
        'template' => "<tr><th style='width: 200px;'>{label}</th><td>{value}</td></tr>",
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
        'attributes' => [
            'vod_id',
            'list.list_name',
            'vod_name',
            'vod_ename',
            'vod_url:ntext',
            'vod_title',
            'vod_keywords',
            'vod_type',
            'vod_actor',
            'vod_director',
            'vod_content:raw',
            [
                'attribute' => 'vod_pic',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->vod_pic) {
                        return '<img width="100" src="'. $model->vod_pic.'">';
                    }
                    return '';
                }
            ],
            'vod_pic_bg',
            'vod_pic_slide',
            'vod_area',
            'vod_language',
            'vod_year',
            'vod_continu',
            'vod_total',
            'vod_isend',
            'vod_addtime:datetime',
            'vod_filmtime:datetime',
            'vod_hits',
            'vod_hits_day',
            'vod_hits_week',
            'vod_hits_month',
            [
                'attribute' => 'vod_stars',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->getStar();
                }
            ],
            'vod_status',
            'vod_up',
            'vod_down',
            [
                'attribute' => 'vod_ispay',
                'value' => function($model) {
                    return $model->getChargeText();
                }
            ],
            'vod_price',
            'vod_trysee',
            //'vod_play',
            //'vod_server',
            //'vod_url:ntext',
            //'vod_inputer',
            //'vod_reurl',
            //'vod_jumpurl',
            'vod_letter',
            //'vod_skin',
            'vod_gold',
            'vod_golder',
            'vod_length',
            'vod_multiple',
            //'vod_weekday',
            //'vod_series',
            //'vod_copyright',
            'vod_state',
            'vod_version',
            'vod_douban_id',
            'vod_douban_score',
            'vod_scenario:ntext',
            [
                'attribute' => 'vod_home',
                'value' => function($model) {
                    return $model->vod_home ? '是':"否";
                }
            ],
            'vod_imdb_id',
            'vod_imdb_score',
            'vod_fill_flag'
        ],
    ])*/

    ?>


</div>
