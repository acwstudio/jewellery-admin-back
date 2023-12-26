<?php

declare(strict_types=1);

return [
    'queues' => [
        'orders' => env('EXPORT_QUEUES_ORDERS', ''),
        'surveys' => env('EXPORT_QUEUES_SURVEYS', ''),
        'payment_statuses' => env('EXPORT_QUEUES_PAYMENT_STATUSES', ''),
        'collections' => env('EXPORT_QUEUES_COLLECTIONS', ''),
    ]
];
