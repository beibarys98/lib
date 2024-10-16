<?php
return [
    'timeZone' => 'Asia/Tashkent',
    'on beforeRequest' => function ($event) {
        Yii::$app->language = Yii::$app->session->get('language', 'kz-KZ');
    },

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Asia/Tashkent',
            'timeZone' => 'Asia/Tashkent',
            'locale' => 'ru_RU', // Set the locale to Russian
            'dateFormat' => 'php:d.m.Y', // Example: 'php:d.m.Y' for 25.08.2024 format
            'datetimeFormat' => 'php:d.m.Y H:i:s', // Example: 'php:d.m.Y H:i:s' for 25.08.2024 14:30:00 format
            'timeFormat' => 'php:H:i:s', // Example: 'php:H:i:s' for 14:30:00 format
        ],

        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
    ],
];
