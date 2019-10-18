<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

use Jmhc\Restful\Utils\Env;

return [

    'driver' => 'rabbitmq',

    /*
     * Set to "horizon" if you wish to use Laravel Horizon.
     */
    'worker' => Env::get('jmhc.rabbitmq.worker', 'default'),

    'dsn' => Env::get('jmhc.rabbitmq.dsn', null),

    /*
     * Could be one a class that implements \Interop\Amqp\AmqpConnectionFactory for example:
     *  - \EnqueueAmqpExt\AmqpConnectionFactory if you install enqueue/amqp-ext
     *  - \EnqueueAmqpLib\AmqpConnectionFactory if you install enqueue/amqp-lib
     *  - \EnqueueAmqpBunny\AmqpConnectionFactory if you install enqueue/amqp-bunny
     */

    'factory_class' => Enqueue\AmqpLib\AmqpConnectionFactory::class,

    'host' => Env::get('jmhc.rabbitmq.host', 'rabbitmq'),
    'port' => Env::get('jmhc.rabbitmq.port', 5672),

    'vhost' => Env::get('jmhc.rabbitmq.vhost', '/'),
    'login' => Env::get('jmhc.rabbitmq.login', 'guest'),
    'password' => Env::get('jmhc.rabbitmq.password', 'guest'),

    'queue' => Env::get('jmhc.rabbitmq.queue', 'default'),

    'options' => [
        'routing_key' => Env::get('jmhc.rabbitmq.routing_key'),

        'exchange' => [

            'name' => Env::get('jmhc.rabbitmq.exchange_name'),

            /*
             * Determine if exchange should be created if it does not exist.
             */

            'declare' => Env::get('jmhc.rabbitmq.exchange_declare', true),

            /*
             * Read more about possible values at https://www.rabbitmq.com/tutorials/amqp-concepts.html
             */

            'type' => Env::get('jmhc.rabbitmq.exchange_type', \Interop\Amqp\AmqpTopic::TYPE_DIRECT),
            'passive' => Env::get('jmhc.rabbitmq.exchange_passive', false),
            'durable' => Env::get('jmhc.rabbitmq.exchange_durable', true),
            'auto_delete' => Env::get('jmhc.rabbitmq.exchange_autodelete', false),
            'arguments' => Env::get('jmhc.rabbitmq.exchange_arguments'),
        ],

        'queue' => [

            /*
             * Determine if queue should be created if it does not exist.
             */

            'declare' => Env::get('jmhc.rabbitmq.queue_declare', true),

            /*
             * Determine if queue should be binded to the exchange created.
             */

            'bind' => Env::get('jmhc.rabbitmq.queue_declare_bind', true),

            /*
             * Read more about possible values at https://www.rabbitmq.com/tutorials/amqp-concepts.html
             */

            'passive' => Env::get('jmhc.rabbitmq.queue_passive', false),
            'durable' => Env::get('jmhc.rabbitmq.queue_durable', true),
            'exclusive' => Env::get('jmhc.rabbitmq.queue_exclusive', false),
            'auto_delete' => Env::get('jmhc.rabbitmq.queue_autodelete', false),
            'arguments' => Env::get('jmhc.rabbitmq.queue_arguments'),
        ],
    ],

    /*
     * Determine the number of seconds to sleep if there's an error communicating with rabbitmq
     * If set to false, it'll throw an exception rather than doing the sleep for X seconds.
     */

    'sleep_on_error' => Env::get('jmhc.rabbitmq.error_sleep', 5),

    /*
     * Optional SSL params if an SSL connection is used
     * Using an SSL connection will also require to configure your RabbitMQ to enable SSL. More details can be founds here: https://www.rabbitmq.com/ssl.html
     */

    'ssl_params' => [
        'ssl_on' => Env::get('jmhc.rabbitmq.ssl', false),
        'cafile' => Env::get('jmhc.rabbitmq.ssl_cafile', null),
        'local_cert' => Env::get('jmhc.rabbitmq.ssl_localcert', null),
        'local_key' => Env::get('jmhc.rabbitmq.ssl_localkey', null),
        'verify_peer' => Env::get('jmhc.rabbitmq.ssl_verify_peer', true),
        'passphrase' => Env::get('jmhc.rabbitmq.ssl_passphrase', null),
    ],

];