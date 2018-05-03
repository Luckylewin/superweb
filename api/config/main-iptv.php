<?php
use api\components\Formatter;


$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'defaultRoute' => 'api/index',
    'bootstrap' => ['log'],
    'layout' => false,
    'modules' => [],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'cookieValidationKey' => '*&#$^$%&^#$%%^&',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;

                if ($response->data !== null) {

                    if ($response->isSuccessful == false) {

                        if ( $response instanceof \yii\web\Response) {
                            $httpCode = $response->getStatusCode();
                            if (is_object($response->data) || (!isset($response->data['code']) && $response->data['code']) ) {
                                $statusCode =  $response->getStatusCode();
                            } else {
                                $statusCode = $response->data['code'];
                            }
                           // $statusCode = $response->getStatusCode();
                            $message = is_array($response->data) ? $response->data['message'] : $statusCode;

                        } else {
                            $httpCode = $response->data->statusCode;
                            $statusCode = isset($response->data['code']) && $response->data['code'] ? $response->data['code'] : $response->data->statusCode;
                            $message = isset($response->data['code'])? $response->data['message'] : $response->data->statusCode;
                        }

                        $response->statusCode = $httpCode;
                        $response->data = Formatter::format(null, $statusCode, $message);

                    } else {
                        $response->statusCode = 200;
                        if (!isset($response->data['code'])) {
                            $response->data = Formatter::format($response->data);
                        }
                    }
                }
            },
        ],

        'user' => [
            'identityClass' => 'backend\models\Mac',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
            'enableSession' => false,
            'loginUrl' => null,
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
            'errorAction' => 'base/error',
        ],

        //配置数据库
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=superweb',
            'username' => 'root',
            'password' => '12345678',
            'charset' => 'utf8',
            'tablePrefix' => 'yii2_',

        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['type', 'banner', 'user', 'vod', 'recommend'],
                    'except' => ['delete','update','create'],
                    'extraPatterns' => [
                        'GET home' => 'home'
                    ]
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['apk'],
                    'pluralize' => false, //关闭复数形式
                    'except' => ['delete','update', 'create', 'view'],
                    'extraPatterns' => [
                        'GET upgrade' => 'upgrade'
                    ]

                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'auth',
                    'pluralize' => false, //关闭复数形式
                    'except' => ['delete','update', 'create'],
                    'extraPatterns' => [
                        'POST token' => 'token',
                        'GET test' => 'test'
                    ]
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'order',
                    'pluralize' => false, //关闭复数形式
                    'except' => ['delete','update', 'create'],
                    'extraPatterns' => [
                        'POST buy' => 'buy',
                        'POST upgrade' => 'upgrade',
                        'GET price' => 'price'
                    ]
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'pay',
                    'pluralize' => false,
                    'except' => ['view','update','delete','create','index'],
                    'extraPatterns' => [
                        'POST notify' => 'notify',
                        'POST pay' => 'pay',
                        'POST vip' => 'vip'
                    ]
                ]
            ],
        ],

    ],
    'params' => $params,
];
