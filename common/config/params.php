<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    'nginx' => [
        'upload_dir' => '/home/upload/',
        'media_port' => 10082,
        'secret' => 'topthinker'
    ],

    //阿里云OSS配置
    'OSS' =>[
        'ACCESS_ID'=> '',    //ID
        'ACCESS_KEY' => '', // KEY
        'ENDPOINT'=>'oss-cn-shenzhen.aliyuncs.com',//指定区域
        'BUCKET'=>'yii2shop',//bucket
    ],

    // 百度翻译配置
    'BAIDU_TRANSLATE' => [
        'APP_ID' => '',//appID
        'SEC_KEY' => '' //密钥
    ]
];
