<?php

declare(strict_types=1);

use App\Modules\Collections\Support\DataNormalizer\Monolith\CollectionProductDataNormalizer;
use App\Modules\Collections\Support\DataProvider\Monolith\CollectionProductDataProvider;
use App\Modules\Collections\Support\DataNormalizer\RabbitMQ\CollectionDataNormalizer;

return [
    'exclude_ids' => env('COLLECTIONS_EXCLUDE_IDS'),
    'products' => [
        'limit' => (int)env('COLLECTIONS_PRODUCTS_LIMIT', 14)
    ],
    'import' => [
        'products' => [
            'data_provider' => CollectionProductDataProvider::class,
            'data_normalizer' => CollectionProductDataNormalizer::class,
            'per_page' => (int)env('COLLECTIONS_IMPORT_PRODUCTS_PER_PAGE', 100)
        ],
        'collections' => [
            'update' => [
                'only_id' => (bool)env('COLLECTIONS_IMPORT_COLLECTIONS_UPDATE_ONLY_ID', false)
            ],
            'data_normalizer' => CollectionDataNormalizer::class
        ],
    ],
];
