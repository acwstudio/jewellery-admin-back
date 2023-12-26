<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\Sale;

use App\Modules\Promotions\Modules\Sales\Models\Sale;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'promotions_sales_sale_data',
    description: 'Акция',
    type: 'object'
)]
class SaleData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
        #[Property(property: 'description', type: 'string', nullable: true)]
        public readonly ?string $description,
        #[Property(property: 'started_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $started_at,
        #[Property(property: 'expired_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $expired_at,
    ) {
    }

    public static function fromModel(Sale $model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->slug,
            $model->promotion->description,
            $model->promotion->condition->start_at,
            $model->promotion->condition->finish_at
        );
    }
}
