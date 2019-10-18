<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

use Jmhc\Restful\Utils\Env;

return [
    'driver'   => 'mongodb',
    'host'     => Env::get('jmhc.mongodb.host', 'mongo'),
    'port'     => Env::get('jmhc.mongodb.port', 27017),
    'database' => Env::get('jmhc.mongodb.database', 'mongo'),
    'username' => Env::get('jmhc.mongodb.username', ''),
    'password' => Env::get('jmhc.mongodb.password', ''),
    'options'  => [
        'database' => Env::get('jmhc.mongodb.auth_database', 'admin'),
    ]
];
