<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '角色授权';
$this->params['breadcrumbs'][] = \Yii::t('backend','Admin Setting');
$this->params['breadcrumbs'][] = $this->title;
/* @var $treeArr */
/* @var $authRules */
/* @var $role */
?>

<style>
    input[type="checkbox"]{
        -webkit-appearance: none;
        vertical-align:middle;
        margin-top:0;
        background:#fff;
        border:#999 solid 1px;
        border-radius: 3px;
        min-height: 18px;
        min-width: 18px;
        margin-right: 1px;
    }
    .flex {
        margin-bottom: 3px;
    }
    input[type="checkbox"]:checked {
        background: #5bc0de;
    }


    .grad{
        background: -webkit-linear-gradient(left, #ccc , #eee); /* Safari 5.1 - 6.0 */
        background: -o-linear-gradient(right, #ccc , #eee); /* Opera 11.1 - 12.0 */
        background: -moz-linear-gradient(right, #ccc , #eee); /* Firefox 3.6 - 15 */
        background: linear-gradient(to right,  #ccc , #eee); /* 标准的语法 */
    }

</style>
<div class="role-index">
    <?php ActiveForm::begin(); ?>
    <table class="table table-advance table-hover">
        <thead>
        <tr>
            <th class="table-head" colspan="2"><?=$this->title.': '.$role;?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($treeArr as $tree) {
            echo '<tr class="grad">';
            echo '<td width="30%">
                  <label>'.
                    Yii::t('backend', $tree['name']).
                  '</label>
                   </td>
                   <td></td>
                  </tr>';

            if(empty($tree['_child'])) continue;

            foreach($tree['_child'] as $childes) {
                if($childes['pid'] != $tree['id']) continue;
                echo '<tr>
                <td style="padding-left: 50px;"><label>
                <input type="checkbox" class="all" data-id="'.$childes['id'].'" name="rules[]" value="' . $childes['url'] . '" '.(in_array($childes['url'], $authRules) ? 'checked' : '').'> ' . Yii::t('backend', $childes['name']) . '</label></td>
                <td>';

                if(empty($childes['_child'])) continue;
                    foreach($childes['_child'] as $child) {
                        $pid = $child['pid'];
                        if($pid  != $childes['id']) continue;

                        $childUrl = $child['url'];

                        echo "<div class='flex'><input type='checkbox' class='node' data-parent='{$pid}' name='rules[]' value='{$childUrl}' " . (in_array($child['url'], $authRules) ? 'checked' : '') . '><b>' . Yii::t('backend', $child['name']) . '</b></div>';
                    }

                    echo '</td>

                </tr>';
            }
        }
        ?>
        </tbody>
    </table>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php

$js=<<<JS
     $(function (){
     $('.all').change(function(){
        var pid = $(this).data('id');
        $('input[data-parent="'+pid+'"]').attr("checked",this.checked);
     })
   })
JS;

$this->registerJs($js);
?>