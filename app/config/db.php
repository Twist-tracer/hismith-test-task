<?php

if(APP_PROD) {
    $connectionParams = [];
} elseif(APP_DEV) {
    $connectionParams = [
        'example' => [
            'dbname' => 'hismith',
            'user' => 'root',
            'password' => NULL,
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
            'charset' => 'utf8'
        ]
    ];
}

return $connectionParams;