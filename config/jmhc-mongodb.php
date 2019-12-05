<?php

return [
    'driver'   => 'mongodb',
    'host'     => env('MONGODB_HOST', 'mongo'),
    'port'     => env('MONGODB_PORT', 27017),
    'database' => env('MONGODB_DATABASE', 'mongo'),
    'username' => env('MONGODB_USERNAME', ''),
    'password' => env('MONGODB_PASSWORD', ''),
    'options'  => [
        'database' => env('MONGODB_AUTH_DATABASE', 'admin'),
    ]
];
