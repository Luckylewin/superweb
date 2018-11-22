<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/22
 * Time: 10:12
 */

namespace common\widgets\switchInput;


use yii\web\AssetBundle;

/**
 *
 * SwitcherInputWidget::widget([
        'id' => $data['id'],
        'url' => \yii\helpers\Url::to(['menu/update', 'id' => $data['id']]),
        'defaultCheckedStatus' => $data['display'],
        'successTips' => '操作成功',
        'errorTips'   => '操作失败'
   ]);
 * Class SwitchInputAsset
 * @package common\widgets\switchInput
 */
class SwitchInputAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'common/widgets/switchInput/assets/css/switch-input.css',
    ];

    public $js = [
        'common/widgets/switchInput/assets/js/switch-input.js',
        'statics/themes/default-admin/plugins/layer/layer.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}