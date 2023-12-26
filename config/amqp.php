<?php

declare(strict_types=1);

return [
    'connection' => [
        'host' => env('AMQP_HOST', 'rabbitmq'),
        'port' => env('AMQP_PORT', 5672),
        'user' => env('AMQP_USER', 'rmq'),
        'pass' => env('AMQP_PASS', 'rmq'),
        'vhost' => env('AMQP_VHOST', '/'),
        'tls' => env('AMQP_TLS', false),
    ],
];
