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
        <th><?= Yii::t('backend', 'Version') ?></th>

        <th><?= Yii::t('backend', 'Version update content') ?></th>
        <th><?= Yii::t('backend', 'Download link') ?></th>
        <th><?= Yii::t('backend', 'Whether to force an update') ?></th>
        <th><?= Yii::t('backend', 'Operation') ?></th>
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
            <?= \yii\helpers\Html::label($ver->force_update ? Yii::t('backend', 'Yes') : Yii::t('backend', 'No'),null,[
                'class' => 'label label-default'
            ]) ?>
        </td>
        <td>
            <?= \yii\helpers\Html::a(Yii::t('backend', 'View'), ['apk-detail/view','id' => $ver->ID],[
                    'class' => 'btn btn-primary btn-xs'
            ]); ?>
            <?= \yii\helpers\Html::a(Yii::t('backend', 'Edit'), ['apk-detail/update','id' => $ver->ID],[
                'class' => 'btn btn-info btn-xs'
            ]); ?>
            <?= \yii\helpers\Html::a(Yii::t('backend', 'Delete'), ['apk-detail/delete','id' => $ver->ID],[
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

