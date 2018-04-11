<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    //阿里云OSS配置
    'OSS' =>[
        'ACCESS_ID'=> '',    //ID
        'ACCESS_KEY' => '', // KEY
        'ENDPOINT'=>'oss-cn-shenzhen.aliyuncs.com',//指定区域
        'BUCKET'=>'yii2shop',//bucket
    ]
];
