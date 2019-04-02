<?php

return [
    'mysql' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
//        'slaveConfig' => [
//            'username' => 'root',
//            'password' => '',
//            'attributes' => [
//                PDO::ATTR_TIMEOUT => 10,
//            ],
//            'charset' => 'utf8',
//        ],
    ],
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
//        'password' => '',
        'database' => 0,
    ],
    'session' => [
        'class' => 'yii\redis\Session',
        'redis' => [
          'hostname' => '127.0.0.1',
          'port' => 6379,
          'database' => 0,
        ]
    ],

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
