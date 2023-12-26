<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Filter;

use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Schema(schema: 'catalog_filter_product_data', type: 'object')]
class FilterProductData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'price',
            ref: '#/components/schemas/catalog_filter_min_max_data',
            type: 'object',
            nullable: true
        )]
        #[Nullable, ArrayType('min', 'max')]
        public readonly ?MinMaxData $price = null,
        #[Property(
            property: 'prices',
            description: 'Принимает массив ценовых рамок (Формат: 1000-2000,3000-4500)',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $prices = null,
        #[Property(
            property: 'size',
            description: 'Может принимать множественные значения значений через запятую',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $size = null,
        #[Property(property: 'qty_in_stock', nullable: true)]
        #[Nullable, IntegerType, Min(0)]
        public readonly ?int $qty_in_stock = null,
        #[Property(property: 'in_stock', nullable: true)]
        #[Nullable, BooleanType]
        public readonly ?bool $in_stock = null,
        #[Property(
            property: 'category',
            description: 'Может принимать значения id и slug (возможность передачи нескольких однотипных значений через запятую)',
            nullable: true,
            oneOf: [new Schema(type: 'string'), new Schema(type: 'integer')]
        )]
        #[Nullable, StringType]
        public readonly ?string $category = null,
        #[Property(
            property: 'brands',
            description: 'Принимает массив идентификаторов брендов',
            type: 'array',
            items: new Items(type: 'integer'),
            nullable: true
        )]
        #[Nullable, ArrayType]
        public readonly ?array $brands = null,
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
            property: 'feature',
            description: 'Массив свойств',
            type: 'array',
            items: new Items(type: 'string'),
            nullable: true
        )]
        #[Nullable, ArrayType]
        public readonly ?array $feature = null,
        #[Property(
            property: 'sku',
            description: 'Может принимать значения через запятую',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $sku = null,
        #[Property(property: 'has_image', nullable: true)]
        #[Nullable, BooleanType]
        public readonly ?bool $has_image = null,
        #[Property(property: 'is_active', nullable: true)]
        #[Nullable, BooleanType]
        public readonly ?bool $is_active = null,
        #[Property(property: 'search', nullable: true)]
        #[Nullable, StringType]
        public readonly ?string $search = null,
        #[Property(property: 'exclude_sku', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public readonly ?string $exclude_sku = null,
        #[Property(property: 'offer_price_type', type: 'string', enum: OfferPriceTypeEnum::class, nullable: true)]
        #[Nullable, StringType]
        public readonly ?string $offer_price_type = null,
        #[Nullable, BooleanType]
        public readonly ?bool $ignore_common = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'brands.*' => ['integer', 'min:1'],
            'ids.*' => ['integer', 'min:1'],
            'sku.*' => ['string'],
        ];
    }
}
