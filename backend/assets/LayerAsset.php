<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/23
 * Time: 16:08
 */

namespace backend\assets;


use yii\web\AssetBundle;

class LayerAsset extends AssetBundle
{
    public $sourcePath = '@webroot';
    public $js = [
        'statics/themes/default-admin/plugins/layer/layer.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}