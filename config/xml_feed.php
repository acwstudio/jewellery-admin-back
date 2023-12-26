<?php

declare(strict_types=1);

return [
    'product_url' => env('XML_FEED_PRODUCT_URL', 'https://uvi.ru/product/'),
    'folder' => env('XML_FEED_FOLDER', 'feeds'),
    'name' => [
        'avito' => env('XML_FEED_NAME_AVITO', 'avito.xml'),
        'vk' => env('XML_FEED_NAME_VK', 'vk.xml'),
        'yandex' => env('XML_FEED_NAME_YANDEX', 'yandex.xml'),
        'mindbox' => env('XML_FEED_NAME_MINDBOX', 'mindbox.xml'),
    ]
];
