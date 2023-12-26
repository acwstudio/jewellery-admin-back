<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Reservation;

use App\Modules\Catalog\Models\ProductOfferReservation;
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_update_product_offer_reservation_status_data',
    description: 'Обновить статуса резервации торгового предложения продукта',
    required: ['status'],
    type: 'object'
)]
class UpdateProductOfferReservationStatusData extends Data
{
    public function __construct(
        #[MapInputName('id'), IntegerType, Min(1), Exists(ProductOfferReservation::class, 'id')]
        public readonly int $reservation_id,
        #[Property(property: 'status')]
        public readonly OfferReservationStatusEnum $status
    ) {
    }
}
