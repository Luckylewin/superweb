<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/11
 * Time: 16:27
 */

namespace backend\assets;


use yii\web\AssetBundle;

class BootstrapPluginAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $js = [
        'js/bootstrap.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}