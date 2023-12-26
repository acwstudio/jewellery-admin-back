<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Live;

use App\Modules\Catalog\Models\Product;
use App\Modules\Live\Models\LiveProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiveProductFactory extends Factory
{
    protected $model = LiveProduct::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'number' => 0,
            'on_live' => false,
            'started_at' => Carbon::now(),
            'expired_at' => Carbon::now()->addDays(28),
        ];
    }
}
