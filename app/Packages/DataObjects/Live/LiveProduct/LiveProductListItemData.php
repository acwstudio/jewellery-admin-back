<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\LiveProduct;

use App\Modules\Live\Models\LiveProductListItem;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'live_live_product_list_item_data',
    description: 'Данные элемента Прямого эфира',
    type: 'object'
)]
class LiveProductListItemData extends Data
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
        #[Property(property: 'expired_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $expired_at,
    ) {
    }

    public static function fromModel(LiveProductListItem $model): self
    {
        return new self(
            $model->id,
            $model->product_id,
            $model->number,
            $model->started_at,
            $model->expired_at
        );
    }
}
