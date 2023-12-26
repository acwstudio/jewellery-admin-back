<?php

declare(strict_types=1);

use App\Modules\Catalog\Support\DataProvider\Monolith\CategoryDataProvider;
use App\Modules\Catalog\Support\DataNormalizer\Monolith\CategoryDataNormalizer;
use App\Modules\Catalog\Support\DataProvider\Monolith\ProductFilterDataProvider;
use App\Modules\Catalog\Support\DataNormalizer\Monolith\ProductFilterDataNormalizer;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductDataNormalizer;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductLiveDataNormalizer;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductOfferPriceLiveDataNormalizer;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductOfferPriceRegularDataNormalizer;
use App\Modules\Catalog\Support\DataNormalizer\RabbitMQ\ProductOfferStockDataNormalizer;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;

return [
    'import' => [
        'category' => [
            'data_provider' => CategoryDataProvider::class,
            'data_normalizer' => CategoryDataNormalizer::class,
        ],
        'product' => [
            'data_normalizer' => ProductDataNormalizer::class,
        ],
        'product_filter' => [
            'data_provider' => ProductFilterDataProvider::class,
            'data_normalizer' => ProductFilterDataNormalizer::class,
            'per_page' => (int)env('CATALOG_IMPORT_PRODUCT_FILTER_PER_PAGE', 100)
        ],
        'product_offer_price_live' => [
            'data_normalizer' => ProductOfferPriceLiveDataNormalizer::class,
        ],
        'product_offer_price_regular' => [
            'data_normalizer' => ProductOfferPriceRegularDataNormalizer::class,
        ],
        'product_offer_stock' => [
            'data_normalizer' => ProductOfferStockDataNormalizer::class,
        ],
        'product_live' => [
            'data_normalizer' => ProductLiveDataNormalizer::class,
        ],
    ],
    'product_offer_price_priority' => [
        OfferPriceTypeEnum::LIVE,
        OfferPriceTypeEnum::SALE,
        OfferPriceTypeEnum::PROMOCODE,
        OfferPriceTypeEnum::PROMO,
        OfferPriceTypeEnum::REGULAR,
    ],
];
