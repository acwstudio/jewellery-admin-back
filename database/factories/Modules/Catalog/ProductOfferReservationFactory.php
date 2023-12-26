<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferReservation;
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOfferReservationFactory extends Factory
{
    protected $model = ProductOfferReservation::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_offer_id' => ProductOffer::factory(),
            'count' => fake()->randomDigit(),
            'status' => fake()->randomElement(OfferReservationStatusEnum::cases())
        ];
    }
}
