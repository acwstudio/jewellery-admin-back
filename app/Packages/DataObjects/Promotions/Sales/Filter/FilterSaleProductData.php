<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\Filter;

use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

#[Schema(schema: 'promotions_sales_filter_sale_product_data', type: 'object')]
class FilterSaleProductData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'sale_id',
            description: 'Идентификатор акции',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $sale_id = null,
        #[Property(
            property: 'sale_slug',
            description: 'Слаг акции',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $sale_slug = null,
        #[Property(
            property: 'is_active',
            description: 'Активность акционного товара',
            type: 'boolean',
            nullable: true
        )]
        #[Nullable, BooleanType]
        public readonly ?bool $is_active = null,
        #[Property(
            property: 'product_id',
            description: 'Идентификатор товара (множественное через запятую)',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $product_id = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
