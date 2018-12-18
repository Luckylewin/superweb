<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\SubClass */

$this->title = Yii::t('backend', 'Batch Import');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Go Back'), 'url' => Url::to(['main-class/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-class-create">
    <div class="sub-class-form">

        <div class="panel panel-default">
            <div class="panel-heading">
                格式说明 <b><font color="#0000cd">(多条以换行符分开,包含前面的中文)</font></b>
            </div>
            <div class="panel-body">
                <div>

                    <span class="label label-info">1) 最简示例</span><br><br>
                    <span>一级分类:vn,二级分类:vn,频道名称:vtv1,链接:http://test/channel/test.m3u8</span>
                </div>
                <br>
                <div>
                    <span class="label label-info">2) 完整示例</span><br><br>
                    <span>一级分类:vn,二级分类:vn,频道名称:vtv1,频道图标:http://test.com/test.png,频道排序:12,频道可用:1,链接:http://test.com/test.m3u8,解码方式:硬解,链接排序:2,链接可用:1,算法标志:flag1,不支持方案号:rk323|rk324|dvb|6605s</span>
                </div>
           </div>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'text')->textarea([
            'rows' =>10,
            'placeholder' => '请粘贴到这里'
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Submit'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('backend','Go Back'), ['main-class/index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
