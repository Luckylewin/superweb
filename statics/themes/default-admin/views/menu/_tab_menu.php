<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => Yii::t('backend', 'Menu List'),
            'url' => ['menu/index'],
        ],
        [
            'label' => Yii::t('backend', 'Create Menu'),
            'url' => ['menu/create'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);