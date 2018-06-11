<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ParadeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '节目预告';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => Url::to(['parade/index'])];
$this->params['breadcrumbs'][] = $channel;

$this->registerJsFile('https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<div class="parade-index">
<ul id="myTab" class="nav nav-tabs">

    <?php foreach ($data as $key  => $value): ?>
        <?php if($key == 0): ?>
            <li class="active">
        <?php else: ?>
            <li>
        <?php endif; ?>
        <a href="<?= "#table" . $key ?>" data-toggle="tab"><?= $value->parade_date ?></a></li>
    <?php endforeach; ?>

</ul>

<div id="myTabContent" class="tab-content">
    <?php foreach ($data as $key  => $value): ?>
        <?php if($key == 0): ?>
            <div class="tab-pane fade in active" id="<?= "table{$key}" ?>">
        <?php else: ?>
                <div class="tab-pane fade" id="<?= "table{$key}" ?>">
        <?php endif; ?>
                    <br>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <b>来源:</b><?= $value->source; ?>&nbsp;
                                <b>url:</b> <?= Html::a($value->url, $value->url, ['class' => 'btn btn-link', 'target' => '_blank']) ?>
                            </h3>
                        </div>
                        <div class="panel-body">

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>时间</th>
                                    <th>预告内容</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $paradeData = json_decode($value->parade_data, true); ?>

                                <?php foreach ($paradeData as $val): ?>
                                    <tr>
                                        <td><?= $val['parade_time'] ?></td>
                                        <td><?= $val['parade_name'] ?></td>

                                        <td></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
    <?php endforeach; ?>
 </div>

</div>
</div>

<div class="col-md-12">
    <p>
        <?= Html::a('手动添加', ['parade/create', 'name' => $channel], ['class' => 'btn btn-success']) ?>
        <?= Html::a('返回', ['parade/index'], ['class' => 'btn btn-default']) ?>
    </p>
</div>


