<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/23
 * Time: 16:05
 */

namespace common\widgets\ajaxInput;


use yii\web\AssetBundle;

class AjaxInputAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'statics/themes/default-admin/plugins/layer/layer.min.js',
        'common/widgets/ajaxInput/assets/ajax-input.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}