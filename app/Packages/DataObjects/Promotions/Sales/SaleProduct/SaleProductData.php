<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\SaleProduct;

use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'promotions_sales_sale_product_data',
    description: 'Акционный товар',
    type: 'object'
)]
class SaleProductData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'product_id', type: 'integer')]
        public readonly int $product_id,
        #[Property(property: 'sale_id', type: 'integer')]
        public readonly int $sale_id,
        #[Property(property: 'sale_title', type: 'integer')]
        public readonly string $sale_title,
        #[Property(property: 'started_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $started_at,
        #[Property(property: 'expired_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $expired_at,
        #[Property(property: 'promotion_external_id', type: 'string')]
        public readonly string $promotion_external_id,
    ) {
    }

    public static function fromModel(SaleProduct $model): self
    {
        return new self(
            $model->id,
            $model->product_id,
            $model->sale->id,
            $model->sale->title,
            $model->sale->promotion->condition->start_at,
            $model->sale->promotion->condition->finish_at,
            $model->sale->promotion->external_id
        );
    }
}
