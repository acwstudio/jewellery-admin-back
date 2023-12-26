<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\LiveProduct;

use App\Modules\Live\Models\LiveProduct;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'live_short_live_product_data',
    description: 'Упрощенные данные по продукту Прямого эфира',
    type: 'object'
)]
class ShortLiveProductData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'product_id', type: 'integer')]
        public readonly int $product_id,
        #[Property(property: 'number', type: 'integer')]
        public readonly int $number,
        #[Property(property: 'started_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $started_at,
    ) {
    }

    public static function fromModel(LiveProduct $liveProduct): self
    {
        return new self(
            $liveProduct->id,
            $liveProduct->product_id,
            0,
            $liveProduct->started_at
        );
    }
}
