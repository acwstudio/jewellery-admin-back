<?php

declare(strict_types=1);

return [
    'host' => env('OPENSEARCH_HOST', 'localhost'),
    'auth' => [
        'username' => env('OPENSEARCH_USERNAME', 'admin'),
        'password' => env('OPENSEARCH_PASSWORD', 'admin'),
    ],
];
