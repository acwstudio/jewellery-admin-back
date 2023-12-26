<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Price;

use App\Modules\Catalog\Models\ProductOffer;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_update_product_offer_price_is_active_data',
    description: 'Обновление активности цены торгового предложения продукта',
    required: ['is_active'],
    type: 'object'
)]
class UpdateProductOfferPriceIsActiveData extends Data
{
    public function __construct(
        #[MapInputName('id'), IntegerType, Min(1), Exists(ProductOffer::class, 'id')]
        public readonly int $product_offer_id,
        public readonly OfferPriceTypeEnum $type,
        #[Property(property: 'is_active', type: 'boolean')]
        public readonly bool $is_active
    ) {
    }
}
