<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductFeature;

use App\Modules\Catalog\Models\ProductFeature;
use App\Packages\Rules\NotEqual;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_update_product_feature_data',
    description: 'Обновление свойство продукта',
    type: 'object'
)]
class UpdateProductFeatureData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[MapInputName('uuid'), Uuid]
        public readonly string $product_feature_uuid,
        #[Property(property: 'parent_product_feature_uuid', type: 'string', nullable: true)]
        #[Nullable, Uuid, Rule(new NotEqual('product_feature_uuid')), Exists(ProductFeature::class, 'uuid')]
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
