<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions;

use App\Modules\Catalog\Models\Product as CatalogProduct;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleProductFactory extends Factory
{
    protected $model = SaleProduct::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'sale_id' => Sale::factory(),
            'product_id' => CatalogProduct::factory(),
        ];
    }
}
