<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\MyAppAsset;
use \common\models\VodList;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Vod */
/* @var $form yii\widgets\ActiveForm */

MyAppAsset::register($this);
$this->registerJsFile('/statics/js/miniUtils.js')
?>
<style>
    .single-select{

        height: 2.69rem;
        line-height: 3.6rem;


    }
</style>
<div class="vod-form">

    <ul id="myTab" class="nav nav-tabs">
        <li class="active">
            <a href="#home" data-toggle="tab">基本数据</a>
        </li>
        <li><a href="#ios" data-toggle="tab">扩展数据</a></li>

    </ul>
    <?php $form = ActiveForm::begin(); ?>
    <div id="myTabContent" class="tab-content">

        <div class="tab-pane fade in active" id="home">
            <div class="panel panel-default" style="margin-top: 20px;">
                <div class="panel-body">

                    <div class="col-md-4">
                        <!-- 影片名称  -->
                        <?= $form->field($model, 'vod_name')->textInput(['maxlength' => true]) ?>
                        <!-- 影片副标  -->
                        <?= $form->field($model, 'vod_title')->textInput(['maxlength' => true]) ?>
                        <!-- 影片主演  -->
                        <?= $form->field($model, 'vod_actor')->textInput(['maxlength' => true]) ?>
                        <!-- 影片导演  -->
                        <?= $form->field($model, 'vod_director')->textInput(['maxlength' => true]) ?>
                        <!-- 影片别名  -->
                        <?= $form->field($model, 'vod_ename')->textInput(['maxlength' => true]) ?>
                        <!-- 海报剧照  -->
                        <?= $form->field($model, 'vod_pic')->textInput(['maxlength' => true]) ?>
                        <!-- 海报背景  -->
                        <?= $form->field($model, 'vod_pic_bg')->textInput(['maxlength' => true]) ?>
                        <!-- 海报轮播  -->
                        <?= $form->field($model, 'vod_pic_slide')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div class="col-md-4">
                        <!-- 影片分类  -->
                        <?= $form->field($model, 'vod_cid')->dropDownList(ArrayHelper::map(VodList::getAllList(),'list_id', 'list_name')); ?>
                        <!-- 上映日期  -->
                        <?= $form->field($model, 'vod_filmtime')->textInput() ?>
                        <!-- 总共集数  -->
                        <?= $form->field($model, 'vod_total')->textInput() ?>
                        <!-- 影片时长  -->
                        <?= $form->field($model, 'vod_length')->textInput() ?>
                        <!-- 豆瓣ID  -->
                        <?= $form->field($model, 'vod_douban_id')->textInput() ?>
                        <!-- 海报剧照文件上传  -->
                        <?= $form->field($model, 'vod_pic')->textInput(['maxlength' => true]) ?>
                        <!-- 海报背景  -->
                        <?= $form->field($model, 'vod_pic_bg')->textInput(['maxlength' => true]) ?>
                        <!-- 海报轮播  -->
                        <?= $form->field($model, 'vod_pic_slide')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div class="col-md-4">
                        <!-- 影片状态  -->
                        <?= $form->field($model, 'vod_status')->dropDownList($model->showStatus) ?>
                        <!-- 更新日期  -->
                        <?= $form->field($model, 'vod_addtime')->textInput() ?>
                        <!-- 连载集数  -->
                        <?= $form->field($model, 'vod_continu')->textInput(['maxlength' => true]) ?>
                        <!-- 周期  -->
                        <?= $form->field($model, 'vod_weekday')->textInput(['maxlength' => true]) ?>
                        <!-- 豆瓣评分  -->
                        <?= $form->field($model, 'vod_douban_score')->textInput(['maxlength' => true]) ?>
                        <!-- 点播权限 -->
                        <?= $form->field($model, 'vod_ispay')->dropDownList(\common\models\Vod::$chargeStatus) ?>
                        <!-- 单片付费 -->
                        <?= $form->field($model, 'vod_price')->textInput() ?>
                        <!-- 影片试看 -->
                        <?= $form->field($model, 'vod_trysee')->textInput() ?>
                    </div>

                    <div class="col-md-12">
                        <!-- 影片标识  -->
                        <?= $form->field($model, 'vod_reurl')->textInput(['maxlength' => true]) ?>
                        <!-- tags  -->
                        <?= $form->field($model, 'vod_keywords')->textInput(['maxlength' => true]) ?>
                        <!-- 影片系列  -->
                        <?= $form->field($model, 'vod_series')->textInput(['maxlength' => true]) ?>
                        <!-- 扩展分类  -->
                        <?= $form->field($model, 'vod_type', [

                            'template' => '{label}<div class="row">
                                                    <div class="col-sm-3">{input}{error}{hint}</div>
                                                  </div>
                                                  <div class="row multi-select">
                                                    <div class="col-sm-12">'.\common\models\Vod::getSelectList('vod_type') .'</div>' .
                                '</div>'

                        ])->textInput(['maxlength' => true]) ?>
                        <!-- 发行年份  -->
                        <?= $form->field($model, 'vod_year', [

                            'template' => '{label}<div class="row">
                                                    <div class="col-sm-3">{input}{error}{hint}</div><div class="col-sm-7 single-select">'.
                                                        \common\models\Vod::getSelectList('vod_year')
                                                    .'</div></div>'



                        ])->textInput() ?>


                        <!-- 发行地区  -->
                        <?= $form->field($model, 'vod_area', [

                            'template' => '{label}<div class="row">
                                                    <div class="col-sm-3">{input}{error}{hint}</div><div class="col-sm-7 single-select">'.
                                                    \common\models\Vod::getSelectList('vod_area')
                                                  .'</div></div>'

                        ])->textInput() ?>
                        <!-- 影片对白  -->
                        <?= $form->field($model, 'vod_language', [

                            'template' => '{label}<div class="row">
                                                    <div class="col-sm-3">{input}{error}{hint}</div><div class="col-sm-7 single-select">'.
                                                        \common\models\Vod::getSelectList('vod_language')
                                                    .'</div></div>'

                        ])->textInput() ?>
                        <!-- 影片版本  -->
                        <?= $form->field($model, 'vod_version', [

                            'template' => '{label}<div class="row">
                                                    <div class="col-sm-3">{input}{error}{hint}</div><div class="col-sm-7 single-select">'.
                                                        \common\models\Vod::getSelectList('vod_version')
                                                    .'</div></div>'

                        ])->textInput() ?>
                        <!-- 资源类别 -->
                        <?= $form->field($model, 'vod_state',[
                                'template' => '{label}<div class="row">
                                                    <div class="col-sm-3">{input}{error}{hint}</div><div class="col-sm-7 single-select">'.
                                                        \common\models\Vod::getSelectList('vod_state')
                                                    .'</div></div>'
                        ])->textInput(['maxlength' => true]) ?>


                        <!-- 播放地址 -->
                        <?= $form->field($model, 'vod_url')->textarea(['rows' => 6]) ?>
                        <!-- 影片简介 -->
                        <?= $form->field($model, 'vod_content')->textarea(['rows' => 6]) ?>
                        <!-- 是否完结 -->
                        <?php $form->field($model, 'vod_isend')->textInput() ?>
                    </div>



                </div>
            </div>

        </div>
        <div class="tab-pane fade" id="ios">
            <div class="panel panel-default" style="margin-top: 20px;">
                <div class="panel-body">
                    <?= $form->field($model, 'vod_stars')->textInput() ?>

                    <?= $form->field($model, 'vod_hits')->textInput() ?>

                    <?= $form->field($model, 'vod_hits_day')->textInput() ?>

                    <?= $form->field($model, 'vod_hits_week')->textInput() ?>

                    <?= $form->field($model, 'vod_hits_month')->textInput() ?>



                    <?php $form->field($model, 'vod_up')->textInput() ?>

                    <?php $form->field($model, 'vod_down')->textInput() ?>

                    <?php $form->field($model, 'vod_play')->textInput(['maxlength' => true]) ?>

                    <?php $form->field($model, 'vod_server')->textInput(['maxlength' => true]) ?>

                    <?php $form->field($model, 'vod_inputer')->textInput(['maxlength' => true]) ?>

                    <?php $form->field($model, 'vod_jumpurl')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'vod_letter')->textInput(['maxlength' => true]) ?>

                    <?php $form->field($model, 'vod_skin')->textInput(['maxlength' => true]) ?>

                    <?php $form->field($model, 'vod_gold')->textInput(['maxlength' => true]) ?>

                    <?php $form->field($model, 'vod_golder')->textInput() ?>

                    <?php $form->field($model, 'vod_copyright')->textInput() ?>

                    <?= $form->field($model, 'vod_scenario')->textarea(['rows' => 6]) ?>
                </div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php \common\widgets\Jsblock::begin() ?>
<script>


    $(function () {

        $('#myTab li:eq(0) a').tab('show');

        $('.single-select .select').click(function() {
            $('#' + $(this).attr('data-id')).val($(this).text());
        });

        $('.multi-select .select').click(function() {
           var item = $(this).text();
           var data_id = $(this).attr('data-id');
           var dist = $('#' + data_id);
           var curItems = dist.val().split(',').filter(function(item){
               if (item) return item;
           });

           if (miniUtils.in_array(item, curItems)) {
               curItems.remove(item);
               dist.val(curItems.join(','));
           } else if(curItems.length < 1) {
               dist.val(item);
           } else {
               dist.val(dist.val() + ',' + item);
           }

        });
    });
</script>
<?php \common\widgets\Jsblock::end() ?>