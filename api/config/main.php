<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
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
              /* 允许跨域请求
                header("Access-Control-Allow-Origin: http://runapi.showdoc.cc");
                header("Access-Control-Allow-Credentials : true");
                header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie");*/
                if ($response->data !== null) {
                    if ($response->isSuccessful == false) {
                        $responseData = $response->data;
                        if (is_object($responseData)) {

                            $statusCode = isset($response->data->statusCode) ? $response->data->statusCode : $response->statusCode ;
                            $response->statusCode = $statusCode;
                            $response->data = [
                                'error' => $statusCode
                            ];
                        } elseif ( $response instanceof \yii\web\Response) {
                            $response->statusCode = 200;
                            $response->data = [
                                'error' => $response->data
                            ];
                        } else {

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
                   'controller' => ['type', 'vod', 'banner', 'user','order']
               ],
               [
                   'class' => 'yii\rest\UrlRule',
                   'controller' => 'user',
                   'pluralize' => false, //关闭复数形式
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
