<?php

declare(strict_types=1);

return [
    'default' => env('OPENSEARCH_CONNECTION', 'default'),
    'connections' => [
        'default' => [
            'hosts' => [
                env('OPENSEARCH_HOST', 'localhost:9200'),
            ],
            'basicAuthentication' => [
                env('OPENSEARCH_USERNAME'),
                env('OPENSEARCH_PASSWORD'),
            ],
            'SSLVerification' => env('OPENSEARCH_SSL_VERIFICATION', false),
        ],
    ],
];