<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => \Yii::t('backend','Role'),
            'url' => ['role/index'],
        ],
        [
            'label' => \Yii::t('backend','Create Role'),
            'url' => ['role/create'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);