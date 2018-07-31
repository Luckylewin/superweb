<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => \Yii::t('backend','Admin Management'),
            'url' => ['admin/index'],
        ],
        [
            'label' => \Yii::t('backend','Create Admin'),
            'url' => ['admin/create'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);