<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/3/15
 * Time: 16:41
 */
$data = $model->version;

?>


<table class="table table-bordered" style="margin-top: 20px;">
    <thead>
    <tr>
        <th>版本</th>

        <th>更新内容</th>
        <th>下载地址</th>
        <th>是否强制更新</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>

    <?php if($data): ?>
    <?php foreach ($data as $ver): ?>
    <tr>
        <td><?= $ver->ver; ?></td>

        <td>
            <?= mb_strcut($ver->content,0,50) . '...' ?>
        </td>

        <td>
            <?= \yii\helpers\Html::a($ver->url, \common\components\Func::getAccessUrl($ver->url)) ?>
        </td>
        <td>
            <?= \yii\helpers\Html::label($ver->force_update ? '是' : '否',null,[
                'class' => 'label label-default'
            ]) ?>
        </td>
        <td>
            <?= \yii\helpers\Html::a('查看', ['apk-detail/view','id' => $ver->ID],[
                    'class' => 'btn btn-primary btn-xs'
            ]); ?>
            <?= \yii\helpers\Html::a('编辑', ['apk-detail/update','id' => $ver->ID],[
                'class' => 'btn btn-info btn-xs'
            ]); ?>
            <?= \yii\helpers\Html::a('删除', ['apk-detail/delete','id' => $ver->ID],[
                'class' => 'btn btn-danger btn-xs'
            ]); ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
         <td colspan="6" class="text-center">没有发布过版本</td>
    </tr>
    <?php endif; ?>
    </tbody>
</table>

