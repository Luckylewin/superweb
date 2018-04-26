<?php
return [
    'adminEmail' => 'admin@example.com',
    'ALI_PAY' => [
        'app_id' => '',
        'notify_url' => '',
        'return_url' => '',
        'ali_public_key' => '',// 加密方式： **RSA2**
        'private_key' => '',
        /*'log' => [
            // optional
            'file' => Yii::getAlias('@storage/runtime/') . '/logs/alipay.log',
            'level' => 'debug'
        ],*/
        'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
    ]
];
