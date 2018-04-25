<?php
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

                            $statusCode = $response->getStatusCode();
                            $response->statusCode = $statusCode;

                            if (is_array($response->data)) {
                                unset($response->data['type']);
                                unset($response->data['code']);
                                $response->data = [
                                    'error' => $response->data
                                ];
                            } else {
                                $response->data = $statusCode;
                            }

                        } else {
                            $responseData = $response->data;
                            $response->statusCode = isset($responseData['code'])? $responseData['status'] : $response->data->statusCode;
                            $response->data = [
                                'error' => isset($responseData['code'])? $responseData['message'] : $response->data->statusCode,
                            ];
                        }
                        
                    } else {
                        $response->statusCode = 200;

                    }
                }
            },
        ],

        'user' => [
            'identityClass' => 'common\models\User',
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
            'enableStrictParsing' => false,
            'rules' => [
               [
                   'class' => 'yii\rest\UrlRule',
                   'controller' => ['type', 'vod', 'banner', 'user','order'],
                   'except' => ['delete','update','create']
               ],
               [
                   'class' => 'yii\rest\UrlRule',
                   'controller' => 'user',
                   'pluralize' => false, //关闭复数形式
                   'except' => ['delete','update', 'create'],
                   'extraPatterns' => [
                       'POST login' => 'login',
                       'POST signup' => 'signup',
                   ]
               ],
           ],
        ],

    ],
    'params' => $params,
];
