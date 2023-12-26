<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\Filter;

use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Data;

#[Schema(schema: 'live_filter_live_product_data', type: 'object')]
class FilterLiveProductData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'is_active',
            description: 'Активность товаров Прямого эфира',
            type: 'boolean',
            nullable: true
        )]
        #[Nullable, BooleanType]
        public readonly ?bool $is_active = null,
        #[Property(
            property: 'ids',
            description: 'Принимает массив идентификаторов товаров',
            type: 'array',
            items: new Items(type: 'integer'),
            nullable: true
        )]
        #[Nullable, ArrayType]
        public readonly ?array $ids = null,
        #[Property(
            property: 'on_live',
            description: 'Товары в Прямом эфире',
            type: 'boolean',
            nullable: true
        )]
        #[Nullable, BooleanType]
        public readonly ?bool $on_live = null,
        #[Property(
            property: 'last_days',
            description: 'Товары за последние дни в Прямом эфире',
            type: 'integer',
            nullable: true
        )]
        #[Nullable, IntegerType]
        public readonly ?int $last_days = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
