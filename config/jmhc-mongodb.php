<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

use Jmhc\Restful\Utils\Env;

return [
    'driver'   => 'mongodb',
    'host'     => Env::get('mongodb.host', 'mongo'),
    'port'     => Env::get('mongodb.port', 27017),
    'database' => Env::get('mongodb.database', 'mongo'),
    'username' => Env::get('mongodb.username', ''),
    'password' => Env::get('mongodb.password', ''),
    'options'  => [
        'database' => Env::get('mongodb.auth_database', 'admin'),
    ]
];
