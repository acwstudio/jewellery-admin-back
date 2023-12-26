<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Reservation;

use App\Modules\Catalog\Models\ProductOffer;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_create_product_offer_reservation_data',
    description: 'Создание резервации торгового предложения продукта',
    required: ['count'],
    type: 'object'
)]
class CreateProductOfferReservationData extends Data
{
    public function __construct(
        #[MapInputName('id'), IntegerType, Min(1), Exists(ProductOffer::class, 'id')]
        public readonly int $product_offer_id,
        #[Property(property: 'count', type: 'integer')]
        #[IntegerType, Min(1)]
        public readonly int $count
    ) {
    }
}
