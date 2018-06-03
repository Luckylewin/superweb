<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ParadeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '节目预告';
$this->params['breadcrumbs'][] = $this->title;
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
    <?php endforeach; ?>
 </div>

</div>
</div>
<?= Html::a('返回', ['parade/index'], ['class' => 'btn btn-default']) ?>


