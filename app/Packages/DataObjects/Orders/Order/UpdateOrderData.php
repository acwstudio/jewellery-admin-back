<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Order;

use App\Packages\Enums\Orders\OrderStatusEnum;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'orders_update_order_data', type: 'object')]
class UpdateOrderData extends Data
{
    public function __construct(
        #[Property('id', type: 'integer')]
        public readonly int $id,
        #[Property('status', type: 'string')]
        public readonly OrderStatusEnum $status,
        #[Property('status_date', type: 'string', nullable: true)]
        public readonly ?Carbon $status_date = null,
        #[Property('external_id', type: 'string', nullable: true)]
        public readonly ?string $external_id = null,
    ) {
    }
}
