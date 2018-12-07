<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => '基本配置',
            'url' => ['config/basic'],
        ],
        [
            'label' => '邮箱',
            'url' => ['config/send-mail'],
        ],
        [
            'label' => '第三方配置',
            'url' => ['config/other'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);