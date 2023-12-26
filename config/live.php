<?php

declare(strict_types=1);

return [
    'product' => [
        'expire_days' => (int)env('LIVE_PRODUCT_EXPIRE_DAYS', 28),
        'last_days' => (int)env('LIVE_PRODUCT_LAST_DAYS', 5)
    ],
];
