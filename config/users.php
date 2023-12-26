<?php

declare(strict_types=1);

use App\Modules\Users\Support\DataNormalizer\UsersDataNormalizer;
use App\Modules\Users\Support\DataProvider\UsersDataProvider;

return [
    'products' => [
        'limit' => (int)env('COLLECTIONS_PRODUCTS_LIMIT', 14)
    ],
    'import' => [
        'users' => [
            'data_provider' => UsersDataProvider::class,
            'data_normalizer' => UsersDataNormalizer::class,
        ],
    ],
];
