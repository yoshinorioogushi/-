<?php

use function Cake\Core\env;

return [
    'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),

    'Security' => [
        'salt' => env('SECURITY_SALT', 'a-very-long-random-salt-string-here-12345678'),
    ],

    'Datasources' => [
        'default' => [
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('MYSQLPORT', '3306'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASSWORD', 'password'),
            'database' => env('DB_NAME', 'myapp'),
        ],
    ],
];
