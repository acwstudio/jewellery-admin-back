<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOfferStockFactory extends Factory
{
    protected $model = ProductOfferStock::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_offer_id' => ProductOffer::factory(),
            'count' => fake()->randomDigit(),
            'is_current' => true,
            'reason' => fake()->randomElement(OfferStockReasonEnum::cases())
        ];
    }
}
