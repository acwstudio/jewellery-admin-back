<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Reservation;

use App\Modules\Catalog\Models\ProductOfferReservation;
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_product_offer_reservation_data',
    description: 'Резервация торгового предложения продукта',
    required: ['id', 'count', 'status'],
    type: 'object'
)]
class ProductOfferReservationData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'count', type: 'integer')]
        public readonly int $count,
        #[Property(property: 'status')]
        public readonly OfferReservationStatusEnum $status
    ) {
    }

    public static function fromModel(ProductOfferReservation $productOfferReservation): self
    {
        return new self(
            $productOfferReservation->id,
            $productOfferReservation->count,
            $productOfferReservation->status
        );
    }
}
