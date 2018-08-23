<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'zh-CN',  //目标语言
    //定义运行缓存文件存放目录
    'runtimePath'  => dirname(dirname(__DIR__)) . '/storage/runtime',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [

        'session' => [
            'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',
            'sessionTable' => 'yii2_session',
        ],
        //资源包管理
        'assetManager' => [
            'basePath' => '@webroot/storage/assets',
            'baseUrl'=>'@web/storage/assets',
            'bundles' => [
                'yii\web\YiiAsset',
                'yii\web\JqueryAsset',
                'yii\bootstrap\BootstrapAsset',
                // you can override AssetBundle configs here
            ],
            //'linkAssets' => true,
            // ...
        ],

        /**
         * 语言包配置
         * 将"源语言"翻译成"目标语言". 注意"源语言"默认配置为 'sourceLanguage' => 'en-US'
         * 使用: \Yii::t('common', 'title'); 将common/messages下的common.php中的title转为对应的中文
         */
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'common' => 'common.php',
                        'frontend' => 'frontend.php',
                        'backend' => 'backend.php',
                    ],
                ],
            ],
        ],



    ],

];
