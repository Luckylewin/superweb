<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'language' => 'en-US',
    'sourceLanguage' => 'en-US',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => [
        'log',
        'queue',
    ],
    'modules' => [
        'db-manager' => [
            'class' => 'bs\dbManager\Module',
            // path to directory for the dumps
            'path' => '@storage/backups',
            // list of registerd db-components
            'dbList' => ['db'],
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => 'EJBy8WRohzpqJY7BTurjQaft2NV-g1cA',
        ],

        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */

        //用户认证组件
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],

        //Rbac权限控制
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        //配置数据库
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=superweb',
            'username' => 'root',
            'password' => '12345678',
            'charset' => 'utf8',
            'tablePrefix' => 'yii2_',
            'enableSchemaCache' => true
        ],

        //阿里云组件
        'Aliyunoss' => [
            'class' => 'common\oss\Aliyunoss',
        ],

        //redis组件
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'password' => '198721',
            'database' => 0,
        ],
        // 格式化组件
        'formatter' => [
            'timeZone' => 'Asia/Shanghai',
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:Y/m/d',
            'datetimeFormat' => 'php:Y/m/d H:i',
            'timeFormat' => 'php:H:i:s',
        ],

        // 队列组件
        'queue' => [
            'class' => \yii\queue\file\Queue::className(),
            'path' => '@storage/runtime/queue',
        ],


        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@webroot/storage/runtime/cache'
        ],
    ],

     //指定时区
    'timeZone'=>'Asia/Shanghai',
    'defaultRoute' => 'index/frame',
    'layout' => 'main',//布局文件 优先级: 控制器>配置文件>系统默认
    'params' => $params,
    'on beforeRequest' => function ($event) {

        Yii::$app->i18n->translations['dbManager'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@common/messages',
        ];

        $cookies = Yii::$app->request->cookies;//注意此处是request
        $language = $cookies->get('language', 'en-US');//设置默认值
        Yii::$app->language = $language;
        return;
    },
];
