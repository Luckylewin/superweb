<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';
require __DIR__ . '/backend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/common/config/main-local.php',
    require __DIR__ . '/backend/config/main.php',
    require __DIR__ . '/backend/config/main-local.php'
);

//定义
$config['components']['view'] = [
    'theme' => [
        'basePath' => '@statics/themes/default-admin',
        'baseUrl' => '@statics/themes/default-admin',
        'pathMap' => [
            '@backend/views' => [
                '@statics/themes/' . 'default-admin' . '/views',
            ],
        ],
    ],
];


(new yii\web\Application($config))->run();
