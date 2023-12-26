<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\SaleProduct;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'promotions_sales_create_or_update_sale_product_data',
    description: 'Создание или обновление товара акции',
    type: 'object'
)]
class CreateOrUpdateSaleProductData extends Data
{
    public function __construct(
        #[Property(property: 'sale_id', type: 'integer')]
        public readonly int $sale_id,
        #[Property(property: 'product_id', type: 'integer')]
        public readonly int $product_id,
    ) {
    }
}
