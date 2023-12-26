<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer;

use App\Modules\Catalog\Models\Product;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_create_product_offer_data',
    description: 'Создание торгового предложения продукта',
    required: ['product_id'],
    type: 'object'
)]
class CreateProductOfferData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'product_id', type: 'integer')]
        #[IntegerType, Min(1), Exists(Product::class, 'id')]
        public readonly int $product_id,
        #[Property(property: 'size', description: 'Размер', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public readonly ?string $size = null,
        #[Property(property: 'weight', description: 'Вес', type: 'string', nullable: true)]
        #[Nullable, StringType]
        public readonly ?string $weight = null
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
