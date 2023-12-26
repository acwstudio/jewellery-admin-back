<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductFeature;

use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_create_product_feature_data',
    description: 'Создание свойство продукта',
    required: ['product_id', 'feature_id'],
    type: 'object'
)]
class CreateProductFeatureData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'product_id', type: 'integer')]
        #[IntegerType, Min(1), Exists(Product::class, 'id')]
        public readonly int $product_id,
        #[Property(property: 'feature_id', type: 'integer')]
        #[IntegerType, Min(1), Exists(Feature::class, 'id')]
        public readonly int $feature_id,
        #[Property(property: 'parent_product_feature_uuid', type: 'string', nullable: true)]
        #[Nullable, Uuid, Exists(ProductFeature::class, 'uuid')]
        public readonly ?string $parent_product_feature_uuid = null,
        #[Property(property: 'value', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public readonly ?string $value = null,
        #[Property(property: 'is_main', type: 'boolean', nullable: true)]
        #[Nullable, BooleanType]
        public readonly ?bool $is_main = null
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
