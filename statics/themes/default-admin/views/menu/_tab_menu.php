<?php
use yii\bootstrap\Nav;

echo Nav::widget([
    'items' => [
        [
            'label' => Yii::t('backend', 'Routes List'),
            'url' => ['menu/routes'],
        ],
        [
            'label' => Yii::t('backend', 'Create Route'),
            'url' => ['menu/create'],
        ],
    ],
    'options' => ['class' => 'nav-tabs'],
]);