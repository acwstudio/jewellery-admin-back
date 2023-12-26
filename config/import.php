<?php

declare(strict_types=1);

return [
    'queues' => [
        'products' => env('IMPORT_QUEUES_PRODUCTS', ''),
        'product_live' => env('IMPORT_QUEUES_PRODUCT_LIVE', ''),
        'product_offer_price_live' => env('IMPORT_QUEUES_PRODUCT_OFFER_PRICE_LIVE', ''),
        'product_offer_price_regular' => env('IMPORT_QUEUES_PRODUCT_OFFER_PRICE_REGULAR', ''),
        'product_offer_stock' => env('IMPORT_QUEUES_PRODUCT_OFFER_STOCK', ''),
        'promotions' => env('IMPORT_QUEUES_PROMOTIONS', ''),
        'pvz' => env('IMPORT_QUEUES_PVZ', ''),
        'order_statuses' => env('IMPORT_QUEUES_ORDER_STATUSES', ''),
        'collections' => env('IMPORT_QUEUES_COLLECTIONS', ''),
    ]
];
