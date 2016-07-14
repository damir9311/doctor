<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../config/web.php'),
    [
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;dbname=doctor_test',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ],
        ]
    ]
);